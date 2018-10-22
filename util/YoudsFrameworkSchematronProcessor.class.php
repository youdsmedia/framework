<?php

// +---------------------------------------------------------------------------+
// | This file is part of the YoudsFramework package.                                   |
// | Copyright (c) 2005-2011 the YoudsFramework Project.                                |
// |                                                                           |
// | For the full copyright and license information, please view the LICENSE   |
// | file that was distributed with this source code. You can also view the    |
// | LICENSE file online at http://www.youds.com/LICENSE.txt                   |
// |   vi: set noexpandtab:                                                    |
// |   Local Variables:                                                        |
// |   indent-tabs-mode: t                                                     |
// |   End:                                                                    |
// +---------------------------------------------------------------------------+

/**
 * YoudsFrameworkSchematronProcessor transforms DOM documents according to ISO Schematron
 * validation and transformation rules into a document containing successful
 * reports and failed assertions.
 *
 * @package    youds
 * @subpackage util
 *
 * @author     Noah Fontes <noah.fontes@bitextender.com>
 * @author     David Zülke <david.zuelke@bitextender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      1.0.0
 *
 * @version    $Id$
 */
class YoudsFrameworkSchematronProcessor extends YoudsFrameworkParameterHolder
{
	const NAMESPACE_SCHEMATRON_ISO = 'http://purl.oclc.org/dsdl/schematron';
	
	const NAMESPACE_SVRL_ISO = 'http://purl.oclc.org/dsdl/svrl';
	
	const NAMESPACE_XSL_1999 = 'http://www.w3.org/1999/XSL/Transform';
	
	/**
	 * @var        array A cache of processor instances.
	 */
	protected static $processors = array();
	
	/**
	 * @var        array The list of Schematron implementation paths to process.
	 */
	protected static $defaultChain = array(
		'%core.youds_dir%/config/schematron/iso_dsdl_include.xsl',
		'%core.youds_dir%/config/schematron/iso_abstract_expand.xsl',
		'%core.youds_dir%/config/schematron/iso_svrl_for_xslt1.xsl'
	);
	
	/**
	 * @var        DOMNode The node the processor will work on.
	 */
	protected $node = null;
	
	/**
	 * Creates a new processor for transforming documents into a Schematron
	 * report.
	 *
	 * @author     Noah Fontes <noah.fontes@bitextender.com>
	 * @since      1.0.0
	 */
	public function __construct(array $chain = null)
	{
		if($chain === null) {
			$chain = static::$defaultChain;
		}
		
		if(!$chain) {
			throw new YoudsFrameworkException('Schematron processor chain must contain at least one path name.');
		}
		
		$this->chain = array_map(array('YoudsFrameworkToolkit', 'expandDirectives'), $chain);
	}
	
	/**
	 * Get an array of all processors.
	 *
	 * @return     array An array of YoudsFrameworkXsltProcessor instances.
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      1.1.0
	 */
	public function getProcessors()
	{
		$retval = array();
		foreach($this->chain as $path) {
			$retval[] = static::getProcessor($path);
		}
		return $retval;
	}
	
	/**
	 * Get a processor instance for the given XSLT path.
	 *
	 * @param      string The file path to the XSL template.
	 *
	 * @return     YoudsFrameworkXsltProcessor The processor instance.
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      1.1.0
	 */
	protected static function getProcessor($path)
	{
		if(!isset(self::$processors[$path])) {
			$processorImpl = new DOMDocument();
			$processorImpl->load($path);
			$processor = new YoudsFrameworkXsltProcessor();
			$processor->importStylesheet($processorImpl);
			self::$processors[$path] = $processor;
		}
		
		return self::$processors[$path];
	}
	
	/**
	 * Sets the node that this processor will transform and validate.
	 *
	 * @param      DOMNode The node to use.
	 *
	 * @author     Noah Fontes <noah.fontes@bitextender.com>
	 * @since      1.0.0
	 */
	public function setNode(DOMNode $node)
	{
		$this->node = $node;
	}
	
	/**
	 * Prepare the given processor for use.
	 * Sets all parameters from this processor class.
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      1.1.0
	 */
	protected function prepareProcessor($processor)
	{
		// ensure everything is a string to make hhvm happy
		$processor->setParameter('', array_map('strval', $this->getParameters()));
	}
	
	/**
	 * Cleanup the given processor after use.
	 * Removes all parameters from this processor class.
	 * Cannot be done in YoudsFrameworkSchematronProcessor::prepareProcessor(), which is
	 * why this must be called in transform().
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      1.1.0
	 */
	protected function cleanupProcessor($processor)
	{
		foreach(array_keys($this->getParameters()) as $parameter) {
			$processor->removeParameter('', $parameter);
		}
	}
	
	/**
	 * Validates the node against a given Schematron validation file.
	 *
	 * @param      DOMDocument The validator to use.
	 *
	 * @return     YoudsFrameworkXmlConfigDomDocument The transformed validation document.
	 *
	 * @author     Noah Fontes <noah.fontes@bitextender.com>
	 * @since      1.0.0
	 */
	public function transform(DOMDocument $schema)
	{
		// do we even have a document?
		if($this->node === null) {
			throw new YoudsFrameworkParseException('Schema validation failed because no document could be parsed');
		}
		
		// is it an ISO Schematron file?
		if(!$schema->documentElement || $schema->documentElement->namespaceURI != self::NAMESPACE_SCHEMATRON_ISO) {
			throw new YoudsFrameworkParseException(sprintf('Schema file "%s" is invalid', $schema->documentURI));
		}
		
		// transform the .sch file to a validation stylesheet using the Schematron implementation
		$validatorImpl = $schema;
		$first = true;
		foreach($this->getProcessors() as $processor) {
			if($first) {
				// set some vars for the schema
				$this->prepareProcessor($processor);
			}
			try {
				$validatorImpl = $processor->transformToDoc($validatorImpl);
			} catch(Exception $e) {
				if($first) {
					$this->cleanupProcessor($processor);
				}
				throw new YoudsFrameworkParseException(sprintf('Could not transform schema file "%s": %s', $schema->documentURI, $e->getMessage()), 0, $e);
			}
			if($first) {
				$this->cleanupProcessor($processor);
				$first = false;
			}
		}
		
		// it transformed fine. but did we get a proper stylesheet instance at all? wrong namespaces can lead to empty docs that only have an XML prolog
		if(!$validatorImpl->documentElement || $validatorImpl->documentElement->namespaceURI != self::NAMESPACE_XSL_1999) {
			throw new YoudsFrameworkParseException(sprintf('Processing using schema file "%s" resulted in an invalid stylesheet', $schema->documentURI));
		}
		
		// all fine so far. let us import the stylesheet
		try {
			$validator = new YoudsFrameworkXsltProcessor();
			$validator->importStylesheet($validatorImpl);
		} catch(Exception $e) {
			throw new YoudsFrameworkParseException(sprintf('Could not process the schema file "%s": %s', $schema->documentURI, $e->getMessage()), 0, $e);
		}
		
		// run the validation by transforming our document using the generated validation stylesheet
		try {
			$result = $validator->transformToDoc($this->node);
		} catch(Exception $e) {
			throw new YoudsFrameworkParseException(sprintf('Could not validate the document against the schema file "%s": %s', $schema->documentURI, $e->getMessage()), 0, $e);
		}
		
		return $result;
	}
}

?>

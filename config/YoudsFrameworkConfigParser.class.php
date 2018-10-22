<?php

// +---------------------------------------------------------------------------+
// | This file is part of the YoudsFramework package.                                   |
// | Copyright (c) 2005-2011 the YoudsFramework Project.                                |
// | Based on the Mojavi3 MVC Framework, Copyright (c) 2003-2005 Sean Kerr.    |
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
 * YoudsFrameworkConfigParser parses XML files using YoudsFrameworkXmlConfigParser, but returns
 * old-style ConfigValueHolders.
 *
 * @package    youds
 * @subpackage config
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.0
 *
 * @deprecated Superseded by YoudsFrameworkXmlConfigParser, will be removed in YoudsFramework 1.1
 *
 * @version    $Id$
 */
class YoudsFrameworkConfigParser
{
	/**
	 * @var        string The encoding of the DOMDocument
	 */
	protected $encoding = 'utf-8';
	
	/**
	 * @var        string The filesystem path to the configuration file.
	 */
	protected $config = '';
	
	/**
	 * @param      string An absolute filesystem path to a configuration file.
	 * @param      array  An associative array of validation information.
	 *
	 * @return     YoudsFrameworkConfigValueHolder The data handlers use to perform tasks.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function parse($config, $validationFile = null)
	{
		// copy path in case convertEncoding() needs to complain about a missing ICONV extension
		$this->config = $config;
		
		$parser = new YoudsFrameworkXmlConfigParser($config, YoudsFrameworkConfig::get('core.environment'), null);
		
		$validation = array(
			YoudsFrameworkXmlConfigParser::STEP_TRANSFORMATIONS_BEFORE => array(),
			YoudsFrameworkXmlConfigParser::STEP_TRANSFORMATIONS_AFTER => array(
				YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_XMLSCHEMA => array(),
			),
		);
		if($validationFile !== null) {
			$validation[YoudsFrameworkXmlConfigParser::STEP_TRANSFORMATIONS_AFTER][YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_XMLSCHEMA][] = $validationFile;
		}
		$doc = $parser->execute(array(), $validation);
		
		// remember encoding for convertEncoding()
		$this->encoding = strtolower($doc->encoding);
		
		$rootRes = new YoudsFrameworkConfigValueHolder();
		
		if($doc->documentElement) {
			$this->parseNodes(array($doc->documentElement), $rootRes);
		}
		
		return $rootRes;
	}

	/**
	 * Iterates through a list of nodes and stores to each node in the
	 * ConfigValueHolder
	 *
	 * @param      mixed An array or an object that can be iterated over
	 * @param      YoudsFrameworkXmlValueHolder The storage for the info from the nodes
	 * @param      bool Whether this list is the singular form of the parent node
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	protected function parseNodes($nodes, YoudsFrameworkConfigValueHolder $parentVh, $isSingular = false)
	{
		foreach($nodes as $node) {
			if($node->nodeType == XML_ELEMENT_NODE && (!$node->namespaceURI || $node->namespaceURI == YoudsFrameworkXmlConfigParser::NAMESPACE_AGAVI_ENVELOPE_0_11)) {
				$vh = new YoudsFrameworkConfigValueHolder();
				$nodeName = $this->convertEncoding($node->localName);
				$vh->setName($nodeName);
				$parentVh->addChildren($nodeName, $vh);

				foreach($node->attributes as $attribute) {
					if((!$attribute->namespaceURI || $attribute->namespaceURI == YoudsFrameworkXmlConfigParser::NAMESPACE_AGAVI_ENVELOPE_0_11)) {
						$vh->setAttribute($this->convertEncoding($attribute->localName), $this->convertEncoding($attribute->nodeValue));
					}
				}

				// there are no child nodes so we set the node text contents as the value for the valueholder
				if($node->getElementsByTagName('*')->length == 0) {
					$vh->setValue($this->convertEncoding($node->nodeValue));
				}

				if($node->hasChildNodes()) {
					$this->parseNodes($node->childNodes, $vh);
				}
			}
		}
	}
	
	/**
	 * Handle encoding for a value, i.e. translate from UTF-8 if necessary.
	 *
	 * @param      string A UTF-8 string value from the DomDocument.
	 *
	 * @return     string A value in the correct encoding of the parsed document.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	protected function convertEncoding($value)
	{
		if($this->encoding == 'utf-8') {
			return $value;
		} elseif($this->encoding == 'iso-8859-1') {
			return utf8_decode($value);
		} elseif(function_exists('iconv')) {
			return iconv('UTF-8', $this->encoding, $value);
		} else {
			throw new YoudsFrameworkParseException('No iconv module available, configuration file "' . $this->config . '" with input encoding "' . $this->encoding . '" cannot be parsed.');
		}
	}
}

?>

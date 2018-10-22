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
 * YoudsFrameworkConfigHandlersConfigHandler allows you to specify configuration handlers
 * for the application or on a module level.
 *
 * @package    youds
 * @subpackage config
 *
 * @author     Dominik del Bondio <ddb@bitxtender.com>
 * @author     Noah Fontes <noah.fontes@bitextender.com>
 * @author     David Zülke <david.zuelke@bitextender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.0
 *
 * @version    $Id$
 */
class YoudsFrameworkConfigHandlersConfigHandler extends YoudsFrameworkXmlConfigHandler
{
	const XML_NAMESPACE = 'http://youds.com/youds/config/parts/config_handlers/1.1';
	
	/**
	 * Execute this configuration handler.
	 *
	 * @param      YoudsFrameworkXmlConfigDomDocument The document to handle.
	 *
	 * @return     string Data to be written to a cache file.
	 *
	 * @throws     <b>YoudsFrameworkUnreadableException</b> If a requested configuration
	 *                                             file does not exist or is not
	 *                                             readable.
	 * @throws     <b>YoudsFrameworkParseException</b> If a requested configuration file is
	 *                                        improperly formatted.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @author     Noah Fontes <noah.fontes@bitextender.com>
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      0.11.0
	 */
	public function execute(YoudsFrameworkXmlConfigDomDocument $document)
	{
		// set up our default namespace
		$document->setDefaultNamespace(self::XML_NAMESPACE, 'config_handlers');
		
		// init our data arrays
		$handlers = array();
		
		foreach($document->getConfigurationElements() as $configuration) {
			if(!$configuration->has('handlers')) {
				continue;
			}
			
			// let's do our fancy work
			foreach($configuration->get('handlers') as $handler) {
				$pattern = $handler->getAttribute('pattern');
				
				$category = YoudsFrameworkToolkit::normalizePath(YoudsFrameworkToolkit::expandDirectives($pattern));
				
				$class = $handler->getAttribute('class');
				
				$transformations = array(
					YoudsFrameworkXmlConfigParser::STAGE_SINGLE => array(),
					YoudsFrameworkXmlConfigParser::STAGE_COMPILATION => array(),
				);
				if($handler->has('transformations')) {
					foreach($handler->get('transformations') as $transformation) {
						$path = YoudsFrameworkToolkit::literalize($transformation->getValue());
						$for = $transformation->getAttribute('for', YoudsFrameworkXmlConfigParser::STAGE_SINGLE);
						$transformations[$for][] = $path;
					}
				}
				
				$validations = array(
					YoudsFrameworkXmlConfigParser::STAGE_SINGLE => array(
						YoudsFrameworkXmlConfigParser::STEP_TRANSFORMATIONS_BEFORE => array(
							YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_RELAXNG => array(
							),
							YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_SCHEMATRON => array(
							),
							YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_XMLSCHEMA => array(
							),
						),
						YoudsFrameworkXmlConfigParser::STEP_TRANSFORMATIONS_AFTER => array(
							YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_RELAXNG => array(
							),
							YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_SCHEMATRON => array(
							),
							YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_XMLSCHEMA => array(
							),
						),
					),
					YoudsFrameworkXmlConfigParser::STAGE_COMPILATION => array(
						YoudsFrameworkXmlConfigParser::STEP_TRANSFORMATIONS_BEFORE => array(
							YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_RELAXNG => array(
							),
							YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_SCHEMATRON => array(
							),
							YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_XMLSCHEMA => array(
							),
						),
						YoudsFrameworkXmlConfigParser::STEP_TRANSFORMATIONS_AFTER => array(
							YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_RELAXNG => array(
							),
							YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_SCHEMATRON => array(
							),
							YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_XMLSCHEMA => array(
							),
						),
					),
				);
				if($handler->has('validations')) {
					foreach($handler->get('validations') as $validation) {
						$path = YoudsFrameworkToolkit::literalize($validation->getValue());
						$type = null;
						if(!$validation->hasAttribute('type')) {
							$type = $this->guessValidationType($path);
						} else {
							$type = $validation->getAttribute('type');
						}
						$for = $validation->getAttribute('for', YoudsFrameworkXmlConfigParser::STAGE_SINGLE);
						$step = $validation->getAttribute('step', YoudsFrameworkXmlConfigParser::STEP_TRANSFORMATIONS_AFTER);
						$validations[$for][$step][$type][] = $path;
					}
				}
				
				$handlers[$category] = isset($handlers[$category])
					? $handlers[$category]
					: array(
						'parameters' => array(),
						);
				$handlers[$category] = array(
					'class' => $class,
					'parameters' => $handler->getYoudsFrameworkParameters($handlers[$category]['parameters']),
					'transformations' => $transformations,
					'validations' => $validations,
				);
			}
		}
		
		$data = array(
			'return ' . var_export($handlers, true),
		);
		
		return $this->generate($data, $document->documentURI);
	}
	
	/**
	 * Convenience method to quickly guess the type of a validation file using its
	 * file extension.
	 *
	 * @param      string The path to the file.
	 *
	 * @return     string An YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_* const value.
	 *
	 * @throws     YoudsFrameworkException If the type could not be determined.
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      1.0.0
	 */
	protected function guessValidationType($path)
	{
		switch(pathinfo($path, PATHINFO_EXTENSION)) {
			case 'rng':
				return YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_RELAXNG;
			case 'rnc':
				return YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_RELAXNG;
			case 'sch':
				return YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_SCHEMATRON;
			case 'xsd':
				return YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_XMLSCHEMA;
			default:
				throw new YoudsFrameworkException(sprintf('Could not determine validation type for file "%s"', $path));
		}
	}
}

?>

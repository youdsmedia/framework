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
 * YoudsFrameworkOutputTypeConfigHandler handles output type configuration files.
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
 * @version    $Id$
 */
class YoudsFrameworkOutputTypeConfigHandler extends YoudsFrameworkXmlConfigHandler
{
	const XML_NAMESPACE = 'http://youds.com/youds/config/parts/output_types/1.1';
	
	/**
	 * Execute this configuration handler.
	 *
	 * @param      YoudsFrameworkXmlConfigDomDocument The document to parse.
	 *
	 * @return     string Data to be written to a cache file.
	 *
	 * @throws     <b>YoudsFrameworkUnreadableException</b> If a requested configuration
	 *                                             file does not exist or is not
	 *                                             readable.
	 * @throws     <b>YoudsFrameworkParseException</b> If a requested configuration file is
	 *                                        improperly formatted.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function execute(YoudsFrameworkXmlConfigDomDocument $document)
	{
		// set up our default namespace
		$document->setDefaultNamespace(self::XML_NAMESPACE, 'output_types');
		
		// remember the config file path
		$config = $document->documentURI;
		
		$data = array();
		$defaultOt = null;
		foreach($document->getConfigurationElements() as $cfg) {
			if(!$cfg->has('output_types')) {
				continue;
			}
			
			$otnames = array();
			foreach($cfg->get('output_types') as $outputType) {
				$otname = $outputType->getAttribute('name');
				if(in_array($otname, $otnames)) {
					throw new YoudsFrameworkConfigurationException('Duplicate Output Type "' . $otname . '" in ' . $config);
				}
				$otnames[] = $otname;
			}

			if(!$cfg->getChild('output_types')->hasAttribute('default')) {
				throw new YoudsFrameworkConfigurationException('No default Output Type specified in ' . $config);
			}

			foreach($cfg->get('output_types') as $outputType) {
				$outputTypeName = $outputType->getAttribute('name');
				$data[$outputTypeName] = isset($data[$outputTypeName]) ? $data[$outputTypeName] : array('parameters' => array(), 'default_renderer' => null, 'renderers' => array(), 'layouts' => array(), 'default_layout' => null, 'exception_template' => null);
				if($outputType->has('renderers')) {
					foreach($outputType->get('renderers') as $renderer) {
						$rendererName = $renderer->getAttribute('name');
						$data[$outputTypeName]['renderers'][$rendererName] = array(
							'class' => $renderer->getAttribute('class'),
							'instance' => null,
							'parameters' => $renderer->getYoudsFrameworkParameters(array()),
						);
					}
					$data[$outputTypeName]['default_renderer'] = $outputType->getChild('renderers')->getAttribute('default');
				}
				if($outputType->has('layouts')) {
					foreach($outputType->get('layouts') as $layout) {
						$layers = array();
						
						if($layout->has('layers')) {
							foreach($layout->get('layers') as $layer) {
								$slots = array();
								
								if($layer->has('slots')) {
									foreach($layer->get('slots') as $slot) {
										$slots[$slot->getAttribute('name')] = array(
											'action' => $slot->getAttribute('action'),
											'module' => $slot->getAttribute('module'),
											'output_type' => $slot->getAttribute('output_type'),
											'request_method' => $slot->getAttribute('method'),
											'parameters' => $slot->getYoudsFrameworkParameters(array()),
										);
									}
								}
								
								$layers[$layer->getAttribute('name')] = array(
									'class' => $layer->getAttribute('class', $this->getParameter('default_layer_class', 'YoudsFrameworkFileTemplateLayer')),
									'parameters' => $layer->getYoudsFrameworkParameters(array()),
									'renderer' => $layer->getAttribute('renderer'),
									'slots' => $slots,
								);
							}
						}
						
						$data[$outputTypeName]['layouts'][$layout->getAttribute('name')] = array(
							'layers' => $layers,
							'parameters' => $layout->getYoudsFrameworkParameters(array()),
						);
					}
					$data[$outputTypeName]['default_layout'] = $outputType->getChild('layouts')->getAttribute('default');
				}
				if($outputType->hasAttribute('exception_template')) {
					$data[$outputTypeName]['exception_template'] = YoudsFrameworkToolkit::expandDirectives($outputType->getAttribute('exception_template'));
					if(!is_readable($data[$outputTypeName]['exception_template'])) {
						throw new YoudsFrameworkConfigurationException('Exception template "' . $data[$outputTypeName]['exception_template'] . '" does not exist or is unreadable');
					}
				}
				$data[$outputTypeName]['parameters'] = $outputType->getYoudsFrameworkParameters($data[$outputTypeName]['parameters']);
			}
			$defaultOt = $cfg->getChild('output_types')->getAttribute('default');
		}

		if(!isset($data[$defaultOt])) {
			$error = 'Configuration file "%s" specifies undefined default Output Type "%s".';
			$error = sprintf($error, $document->documentURI, $defaultOt);
			throw new YoudsFrameworkConfigurationException($error);
		}
		
		$code = array();
		foreach($data as $outputTypeName => $outputType) {
			$code[] = '$ot = new YoudsFrameworkOutputType();';
			$code[] = sprintf(
				'$ot->initialize($this->context, %s, %s, %s, %s, %s, %s, %s);',
				var_export($outputType['parameters'], true),
				var_export($outputTypeName, true),
				var_export($outputType['renderers'], true),
				var_export($outputType['default_renderer'], true),
				var_export($outputType['layouts'], true),
				var_export($outputType['default_layout'], true),
				var_export($outputType['exception_template'], true)
			);
			$code[] = sprintf('$this->outputTypes[%s] = $ot;', var_export($outputTypeName, true));
		}
		$code[] = sprintf('$this->defaultOutputType = %s;', var_export($defaultOt, true));
		
		return $this->generate($code, $config);
	}
}

?>

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
 * YoudsFrameworkCachingConfigHandler compiles the per-action configuration files placed
 * in the "cache" subfolder of a module directory.
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
class YoudsFrameworkCachingConfigHandler extends YoudsFrameworkXmlConfigHandler
{
	const XML_NAMESPACE = 'http://youds.com/youds/config/parts/caching/1.1';
	
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
		$document->setDefaultNamespace(self::XML_NAMESPACE, 'caching');
		
		$cachings = array();
		
		foreach($document->getConfigurationElements() as $cfg) {
			if(!$cfg->has('cachings')) {
				continue;
			}
			
			foreach($cfg->get('cachings') as $caching) {
				$groups = array();
				if($caching->has('groups')) {
					foreach($caching->get('groups') as $group) {
						$groups[] = array('name' => $group->getValue(), 'source' => $group->getAttribute('source', 'string'), 'namespace' => $group->getAttribute('namespace')) ;
					}
				}
				
				$actionAttributes = array();
				if($caching->has('action_attributes')) {
					foreach($caching->get('action_attributes') as $actionAttribute) {
						$actionAttributes[] = $actionAttribute->getValue();
					}
				}
				
				$views = null;
				if($caching->has('views')) {
					$views = array();
					foreach($caching->get('views') as $view) {
						if($view->hasAttribute('module')) {
							$views[] = array('module' => $view->getAttribute('module'), 'view' => $view->getValue());
						} else {
							$views[] = YoudsFrameworkToolkit::literalize($view->getValue());
						}
					}
				}
				
				$outputTypes = array();
				if($caching->has('output_types')) {
					foreach($caching->get('output_types') as $outputType) {
						$layers = null;
						if($outputType->has('layers')) {
							$layers = array();
							foreach($outputType->get('layers') as $layer) {
								$include = YoudsFrameworkToolkit::literalize($layer->getAttribute('include', 'true'));
								if(($layer->has('slots') && !$layer->hasAttribute('include')) || !$include) {
									$slots = array();
									if($layer->has('slots')) {
										foreach($layer->get('slots') as $slot) {
											$slots[] = $slot->getValue();
										}
									}
									$layers[$layer->getAttribute('name')] = $slots;
								} else {
									$layers[$layer->getAttribute('name')] = true;
								}
							}
						}
						
						$templateVariables = array();
						if($outputType->has('template_variables')) {
							foreach($outputType->get('template_variables') as $templateVariable) {
								$templateVariables[] = $templateVariable->getValue();
							}
						}
						
						$requestAttributes = array();
						if($outputType->has('request_attributes')) {
							foreach($outputType->get('request_attributes') as $requestAttribute) {
								$requestAttributes[] = array('name' => $requestAttribute->getValue(), 'namespace' => $requestAttribute->getAttribute('namespace'));
							}
						}
						
						$requestAttributeNamespaces = array();
						if($outputType->has('request_attribute_namespaces')) {
							foreach($outputType->get('request_attribute_namespaces') as $requestAttributeNamespace) {
								$requestAttributeNamespaces[] = $requestAttributeNamespace->getValue();
							}
						}
						
						$otnames = array_map('trim', explode(' ', $outputType->getAttribute('name', '*')));
						foreach($otnames as $otname) {
							$outputTypes[$otname] = array(
								'layers' => $layers,
								'template_variables' => $templateVariables,
								'request_attributes' => $requestAttributes,
								'request_attribute_namespaces' => $requestAttributeNamespaces,
							);
						}
					}
				}
				
				$methods = array_map('trim', explode(' ', $caching->getAttribute('method', '*')));
				foreach($methods as $method) {
					if(!YoudsFrameworkToolkit::literalize($caching->getAttribute('enabled', true))) {
						unset($cachings[$method]);
					} else {
						$values = array(
							'lifetime' => $caching->getAttribute('lifetime'),
							'groups' => $groups,
							'views' => $views,
							'action_attributes' => $actionAttributes,
							'output_types' => $outputTypes,
						);
						$cachings[$method] = $values;
					}
				}
			}
		}
		
		$code = array(
			'$configs = ' . var_export($cachings, true) . ';',
			'if(isset($configs[$index = $container->getRequestMethod()]) || isset($configs[$index = "*"])) {',
			'	$isCacheable = true;',
			'	$config = $configs[$index];',
			'	if(is_array($config["views"])) {',
			'		foreach($config["views"] as &$view) {',
			'			if(!is_array($view)) {',
			'				if($view === null) {',
			'					$view = array(',
			'						"module" => null,',
			'						"name" => null',
			'					);',
			'				} else {',
			'					$view = array(',
			'						"module" => $moduleName,',
			'						"name" => YoudsFrameworkToolkit::evaluateModuleDirective(',
			'							$moduleName,',
			'							"youds.view.name",',
			'							array(',
			'								"actionName" => $actionName,',
			'								"viewName" => $view,',
			'							)',
			'						)',
			'					);',
			'				}',
			'			}',
			'		}',
			'	}',
			'}',
		);
		
		return $this->generate($code, $document->documentURI);
	}
}

?>

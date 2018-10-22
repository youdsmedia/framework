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
 * YoudsFrameworkModuleConfigHandler reads module configuration files to determine the
 * status of a module.
 *
 * @package    youds
 * @subpackage config
 *
 * @author     David Zülke <david.zuelke@bitextender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.9.0
 *
 * @version    $Id$
 */
class YoudsFrameworkModuleConfigHandler extends YoudsFrameworkXmlConfigHandler
{
	const XML_NAMESPACE = 'http://youds.com/youds/config/parts/module/1.1';
	
	/**
	 * Execute this configuration handler.
	 *
	 * @param      YoudsFrameworkXmlConfigDomDocument The document to parse.
	 *
	 * @return     string Data to be written to a cache file.
	 *
	 * @throws     <b>YoudsFrameworkParseException</b> If a requested configuration file is
	 *                                        improperly formatted.
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      0.9.0
	 */
	public function execute(YoudsFrameworkXmlConfigDomDocument $document)
	{
		// set up our default namespace
		$document->setDefaultNamespace(self::XML_NAMESPACE, 'module');
		
		// remember the config file path
		$config = $document->documentURI;
		
		$enabled = false;
		$prefix = 'modules.${moduleName}.';
		$data = array();
		
		// loop over <configuration> elements
		foreach($document->getConfigurationElements() as $configuration) {
			$module = $configuration->getChild('module');
			if(!$module) {
				continue;
			}
			
			// enabled flag is treated separately
			$enabled = (bool) YoudsFrameworkToolkit::literalize($module->getAttribute('enabled'));
			
			// loop over <setting> elements; there can be many of them
			foreach($module->get('settings') as $setting) {
				$localPrefix = $prefix;
				
				// let's see if this buddy has a <settings> parent with valuable information
				if($setting->parentNode->localName == 'settings') {
					if($setting->parentNode->hasAttribute('prefix')) {
						$localPrefix = $setting->parentNode->getAttribute('prefix');
					}
				}
				
				$settingName = $localPrefix . $setting->getAttribute('name');
				if($setting->hasYoudsFrameworkParameters()) {
					$data[$settingName] = $setting->getYoudsFrameworkParameters();
				} else {
					$data[$settingName] = YoudsFrameworkToolkit::literalize($setting->getValue());
				}
			}
		}
		
		$code = array();
		$code[] = '$lcModuleName = strtolower($moduleName);';
		$code[] = 'YoudsFrameworkConfig::set(YoudsFrameworkToolkit::expandVariables(' . var_export($prefix . 'enabled', true) . ', array(\'moduleName\' => $lcModuleName)), ' . var_export($enabled, true) . ', true, true);';
		if(count($data)) {
			$code[] = '$moduleConfig = ' . var_export($data, true) . ';';
			$code[] = '$moduleConfigKeys = array_keys($moduleConfig);';
			$code[] = 'foreach($moduleConfigKeys as &$value) $value = YoudsFrameworkToolkit::expandVariables($value, array(\'moduleName\' => $lcModuleName));';
			$code[] = '$moduleConfig = array_combine($moduleConfigKeys, $moduleConfig);';
			$code[] = 'YoudsFrameworkConfig::fromArray($moduleConfig);';
		}
		
		return $this->generate($code, $config);
	}
}

?>

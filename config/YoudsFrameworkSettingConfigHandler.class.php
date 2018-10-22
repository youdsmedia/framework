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
 * YoudsFrameworkSettingConfigHandler handles the settings.xml file
 *
 * @package    youds
 * @subpackage config
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @author     Dominik del Bondio <ddb@bitxtender.com>
 * @author     Sean Kerr <skerr@mojavi.org>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.9.0
 *
 * @version    $Id$
 */
class YoudsFrameworkSettingConfigHandler extends YoudsFrameworkXmlConfigHandler
{
	const XML_NAMESPACE = 'http://youds.com/youds/config/parts/settings/1.1';
	
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
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function execute(YoudsFrameworkXmlConfigDomDocument $document)
	{
		// set up our default namespace
		$document->setDefaultNamespace(self::XML_NAMESPACE, 'settings');
		
		// init our data array
		$data = array();
		
		$prefix = 'core.';
		
		foreach($document->getConfigurationElements() as $cfg) {
			// let's do our fancy work
			if($cfg->has('system_actions')) {
				foreach($cfg->get('system_actions') as $action) {
					$name = $action->getAttribute('name');
					$data[sprintf('actions.%s_module', $name)] = $action->getChild('module')->getValue();
					$data[sprintf('actions.%s_action', $name)] = $action->getChild('action')->getValue();
				}
			}
			
			// loop over <setting> elements; there can be many of them
			foreach($cfg->get('settings') as $setting) {
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
					$data[$settingName] = $setting->getLiteralValue();
				}
			}
			
			if($cfg->has('exception_templates')) {
				foreach($cfg->get('exception_templates') as $exception_template) {
					$tpl = YoudsFrameworkToolkit::expandDirectives($exception_template->getValue());
					if(!is_readable($tpl)) {
						throw new YoudsFrameworkConfigurationException('Exception template "' . $tpl . '" does not exist or is unreadable');
					}
					if($exception_template->hasAttribute('context')) {
						foreach(array_map('trim', explode(' ', $exception_template->getAttribute('context'))) as $ctx) {
							$data['exception.templates.' . $ctx] = $tpl;
						}
					} else {
						$data['exception.default_template'] = YoudsFrameworkToolkit::expandDirectives($tpl);
					}
				}
			}
		}

		$code = 'YoudsFrameworkConfig::fromArray(' . var_export($data, true) . ');';

		return $this->generate($code, $document->documentURI);
	}
}

?>

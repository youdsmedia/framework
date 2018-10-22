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
 * Main framework class used for autoloading and initial bootstrapping of YoudsFramework.
 *
 * @package    youds
 * @subpackage core
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.0
 *
 * @version    $Id$
 */
final class YoudsFramework
{
	/**
	 * Startup the YoudsFramework core
	 *
	 * @param      string environment the environment to use for this session.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public static function bootstrap($environment = null)
	{
		// set up our __autoload
		spl_autoload_register(array('YoudsFrameworkAutoloader', 'loadClass'));

		try {
			if($environment === null) {
				// no env given? let's read one from core.environment
				$environment = YoudsFrameworkConfig::get('core.environment');
			} elseif(YoudsFrameworkConfig::has('core.environment') && YoudsFrameworkConfig::isReadonly('core.environment')) {
				// env given, but core.environment is read-only? then we must use that instead and ignore the given setting
				$environment = YoudsFrameworkConfig::get('core.environment');
			}
			
			if($environment === null) {
				// still no env? oh man...
				throw new YoudsFrameworkException('You must supply an environment name to YoudsFramework::bootstrap() or set the name of the default environment to be used in the configuration directive "core.environment".');
			}
			
			// finally set the env to what we're really using now.
			YoudsFrameworkConfig::set('core.environment', $environment, true, true);

			YoudsFrameworkConfig::set('core.debug', false, false);

			if(!YoudsFrameworkConfig::has('core.app_dir')) {
				throw new YoudsFrameworkException('Configuration directive "core.app_dir" not defined, terminating...');
			}

			// define a few filesystem paths
			YoudsFrameworkConfig::set('core.cache_dir', YoudsFrameworkConfig::get('core.app_dir') . '/cache', false, true);

			YoudsFrameworkConfig::set('core.config_dir', YoudsFrameworkConfig::get('core.app_dir') . '/config', false, true);

			YoudsFrameworkConfig::set('core.system_config_dir', YoudsFrameworkConfig::get('core.youds_dir') . '/config/defaults', false, true);

			YoudsFrameworkConfig::set('core.lib_dir', YoudsFrameworkConfig::get('core.app_dir') . '/lib', false, true);

			YoudsFrameworkConfig::set('core.model_dir', YoudsFrameworkConfig::get('core.app_dir') . '/models', false, true);

			YoudsFrameworkConfig::set('core.module_dir', YoudsFrameworkConfig::get('core.app_dir') . '/modules', false, true);

			YoudsFrameworkConfig::set('core.template_dir', YoudsFrameworkConfig::get('core.app_dir') . '/templates', false, true);

			YoudsFrameworkConfig::set('core.cldr_dir', YoudsFrameworkConfig::get('core.youds_dir') . '/translation/data', false, true);

			// autoloads first (will trigger the compilation of config_handlers.xml)
			$autoload = YoudsFrameworkConfig::get('core.config_dir') . '/autoload.xml';
			if(!is_readable($autoload)) {
				$autoload = YoudsFrameworkConfig::get('core.system_config_dir') . '/autoload.xml';
			}
			YoudsFrameworkConfigCache::load($autoload);
			
			// load base settings
			YoudsFrameworkConfigCache::load(YoudsFrameworkConfig::get('core.config_dir') . '/settings.xml');

			// clear our cache if the conditions are right
			if(YoudsFrameworkConfig::get('core.debug')) {
				YoudsFrameworkToolkit::clearCache();

				// load base settings
				YoudsFrameworkConfigCache::load(YoudsFrameworkConfig::get('core.config_dir') . '/settings.xml');
			}

			$compile = YoudsFrameworkConfig::get('core.config_dir') . '/compile.xml';
			if(!is_readable($compile)) {
				$compile = YoudsFrameworkConfig::get('core.system_config_dir') . '/compile.xml';
			}
			// required classes for the framework
			YoudsFrameworkConfigCache::load($compile);

		} catch(Exception $e) {
			YoudsFrameworkException::render($e);
		}
	}
}

?>

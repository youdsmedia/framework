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
 * Pre-initialization script.
 *
 * @package    youds
 *
 * @author     Sean Kerr <skerr@mojavi.org>
 * @author     Mike Vincent <mike@youds.com>
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.9.0
 *
 * @version    $Id$
 */

// load the YoudsFrameworkConfig class
require(__DIR__ . '/config/YoudsFrameworkConfig.class.php');

// check minimum PHP version
YoudsFrameworkConfig::set('core.minimum_php_version', '5.3.2');
if(version_compare(PHP_VERSION, YoudsFrameworkConfig::get('core.minimum_php_version'), '<') ) {
	trigger_error('YoudsFramework requires PHP version ' . YoudsFrameworkConfig::get('core.minimum_php_version') . ' or greater', E_USER_ERROR);
}

// define a few filesystem paths
YoudsFrameworkConfig::set('core.youds_dir', $youds_config_directive_core_youds_dir = __DIR__, true, true);

// default exception template
YoudsFrameworkConfig::set('exception.default_template', $youds_config_directive_core_youds_dir . '/exception/templates/shiny.php');

// required files
require($youds_config_directive_core_youds_dir . '/version.php');
require($youds_config_directive_core_youds_dir . '/core/YoudsFramework.class.php');
require($youds_config_directive_core_youds_dir . '/util/YoudsFrameworkAutoloader.class.php');
// required files for classes YoudsFramework and ConfigCache to run
// consider this the bare minimum we need for bootstrapping
require($youds_config_directive_core_youds_dir . '/util/YoudsFrameworkInflector.class.php');
require($youds_config_directive_core_youds_dir . '/util/YoudsFrameworkArrayPathDefinition.class.php');
require($youds_config_directive_core_youds_dir . '/util/YoudsFrameworkVirtualArrayPath.class.php');
require($youds_config_directive_core_youds_dir . '/util/YoudsFrameworkParameterHolder.class.php');
require($youds_config_directive_core_youds_dir . '/config/YoudsFrameworkConfigCache.class.php');
require($youds_config_directive_core_youds_dir . '/exception/YoudsFrameworkException.class.php');
require($youds_config_directive_core_youds_dir . '/exception/YoudsFrameworkAutoloadException.class.php');
require($youds_config_directive_core_youds_dir . '/exception/YoudsFrameworkCacheException.class.php');
require($youds_config_directive_core_youds_dir . '/exception/YoudsFrameworkConfigurationException.class.php');
require($youds_config_directive_core_youds_dir . '/exception/YoudsFrameworkUnreadableException.class.php');
require($youds_config_directive_core_youds_dir . '/exception/YoudsFrameworkParseException.class.php');
require($youds_config_directive_core_youds_dir . '/util/YoudsFrameworkToolkit.class.php');

// clean up (we don't want collisions with whatever file included us, in case you were wondering about the ugly name of that var)
unset($youds_config_directive_core_youds_dir);

?>

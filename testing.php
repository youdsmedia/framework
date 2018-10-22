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
 * bootstrap file for the YoudsFrameworkTesting
 *
 * @package    youds
 * @subpackage testing
 *
 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
 * @copyright  The YoudsFramework Project
 *
 * @since      1.0.0
 *
 * @version    $Id$
 */

$here = realpath(__DIR__);

$isComposerInstall = false;
foreach(array($here . '/../vendor/autoload.php', $here . '/../../../autoload.php') as $composerAutoload) {
	if(file_exists($composerAutoload)) {
		require($composerAutoload);
		$isComposerInstall = true;
		break;
	}
}

if(!$isComposerInstall) {
	// when the composer autoload was found YoudsFramework will already be loaded
	// load YoudsFramework basics
	require_once($here . '/youds.php');
	
	// changing the init procedure in a minor release... good job, PHPUnit...
	require_once('PHPUnit/Runner/Version.php');
	if(version_compare(PHPUnit_Runner_Version::id(), '3.7.0', '<')) {
		trigger_error('YoudsFramework requires PHPUnit version 3.7.0 or higher', E_USER_ERROR);
	}
	
	// load PHPUnit basics
	require_once('PHPUnit/Autoload.php');
} else {
	// starting with phpunit 4.0 PHPUNIT_COMPOSER_INSTALL doesn't get set in the autoloader anymore, but
	// in the phpunit cli script. we need to make sure it is defined for the process isolation autoloading
	// to work
	if(!defined('PHPUNIT_COMPOSER_INSTALL')) {
		define('PHPUNIT_COMPOSER_INSTALL', $composerAutoload);
	}
}

// testing base classes
require_once($here . '/testing/YoudsFrameworkTesting.class.php');
require_once($here . '/testing/YoudsFrameworkPhpUnitCli.class.php');

?>

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
 * Build system utility class.
 *
 * @package    youds
 * @subpackage build
 *
 * @author     Noah Fontes <noah.fontes@bitextender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      1.0.0
 *
 * @version    $Id$
 */
final class YoudsFrameworkBuild
{
	/**
	 * @var        bool Whether or not the build system has been bootstrapped yet.
	 */
	protected static $bootstrapped = false;
	
	/**
	 * @var        array An associative array of classes and files that
	 *                   can be autoloaded.
	 */
	public static $autoloads = array(
		'YoudsFrameworkBuildException' => 'exception/YoudsFrameworkBuildException.class.php',
		'YoudsFrameworkCheck' => 'check/YoudsFrameworkCheck.class.php',
		'YoudsFrameworkFilesystemCheck' => 'check/YoudsFrameworkFilesystemCheck.class.php',
		'YoudsFrameworkProjectFilesystemCheck' => 'check/YoudsFrameworkProjectFilesystemCheck.class.php',
		'YoudsFrameworkModuleFilesystemCheck' => 'check/YoudsFrameworkModuleFilesystemCheck.class.php',
		'YoudsFrameworkTransform' => 'transform/YoudsFrameworkTransform.class.php',
		'YoudsFrameworkIdentifierTransform' => 'transform/YoudsFrameworkIdentifierTransform.class.php',
		'YoudsFrameworkArraytostringTransform' => 'transform/YoudsFrameworkArraytostringTransform.class.php',
		'YoudsFrameworkStringtoarrayTransform' => 'transform/YoudsFrameworkStringtoarrayTransform.class.php',
		'YoudsFrameworkEventBuildException' => 'event/YoudsFrameworkEventBuildException.class.php',
		'YoudsFrameworkIListener' => 'event/YoudsFrameworkIListener.interface.php',
		'YoudsFrameworkEventDispatcher' => 'event/YoudsFrameworkEventDispatcher.class.php',
		'YoudsFrameworkIEvent' => 'event/YoudsFrameworkIEvent.interface.php',
		'YoudsFrameworkEvent' => 'event/YoudsFrameworkEvent.class.php',
		'YoudsFrameworkProxyProject' => 'phing/YoudsFrameworkProxyProject.class.php',
		'YoudsFrameworkProxyXmlContext' => 'phing/YoudsFrameworkProxyXmlContext.class.php',
		'YoudsFrameworkProxyTarget' => 'phing/YoudsFrameworkProxyTarget.class.php',
		'YoudsFrameworkPhingEventDispatcherManager' => 'phing/YoudsFrameworkPhingEventDispatcherManager.class.php',
		'YoudsFrameworkPhingEventDispatcher' => 'phing/YoudsFrameworkPhingEventDispatcher.class.php',
		'YoudsFrameworkPhingEvent' => 'phing/YoudsFrameworkPhingEvent.class.php',
		'YoudsFrameworkPhingTargetEvent' => 'phing/YoudsFrameworkPhingTargetEvent.class.php',
		'YoudsFrameworkPhingTaskEvent' => 'phing/YoudsFrameworkPhingTaskEvent.class.php',
		'YoudsFrameworkPhingMessageEvent' => 'phing/YoudsFrameworkPhingMessageEvent.class.php',
		'YoudsFrameworkIPhingListener' => 'phing/YoudsFrameworkIPhingListener.interface.php',
		'YoudsFrameworkIPhingTargetListener' => 'phing/YoudsFrameworkIPhingTargetListener.interface.php',
		'YoudsFrameworkIPhingTaskListener' => 'phing/YoudsFrameworkIPhingTaskListener.interface.php',
		'YoudsFrameworkIPhingMessageListener' => 'phing/YoudsFrameworkIPhingMessageListener.interface.php',
		'YoudsFrameworkPhingTargetAdapter' => 'phing/YoudsFrameworkPhingTargetAdapter.class.php',
		'YoudsFrameworkPhingTaskAdapter' => 'phing/YoudsFrameworkPhingTaskAdapter.class.php',
		'YoudsFrameworkPhingMessageAdapter' => 'phing/YoudsFrameworkPhingMessageAdapter.class.php',
		'YoudsFrameworkBuildLogger' => 'phing/YoudsFrameworkBuildLogger.php',
		'YoudsFrameworkProxyBuildLogger' => 'phing/YoudsFrameworkProxyBuildLogger.php'
	);

	/**
	 * Autoloads classes.
	 *
	 * @param      string A class name.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @author     Noah Fontes <noah.fontes@bitextender.com>
	 * @since      1.0.0
	 */
	public static function __autoload($class)
	{
		if(isset(self::$autoloads[$class])) {
			require(__DIR__ . '/' . self::$autoloads[$class]);
		}

		/* If the class isn't loaded by this method, the only other
		 * sane option is to simply let PHP handle it and hope another
		 * handler picks it up. */
	}

	/**
	 * Prepares the build environment classes for use.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @author     Noah Fontes <noah.fontes@bitextender.com>
	 * @since      1.0.0
	 */
	public static function bootstrap()
	{
		if(self::$bootstrapped === false) {
			spl_autoload_register(array('YoudsFrameworkBuild', '__autoload'));
		}
		
		self::$bootstrapped = true;
	}
	
	/**
	 * Retrieves whether the build system has been bootstrapped.
	 *
	 * @return     boolean True if the build system has been bootstrapped, false
	 *                     otherwise.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @author     Noah Fontes <noah.fontes@bitextender.com>
	 * @since      1.0.0
	 */
	public static function isBootstrapped()
	{
		return self::$bootstrapped;
	}
}

?>

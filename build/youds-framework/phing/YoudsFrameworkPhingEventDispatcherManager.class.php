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
 * Manages Phing-based event dispatchers.
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
final class YoudsFrameworkPhingEventDispatcherManager
{
	/**
	 * @var        array List of YoudsFrameworkPhingEventDispatcher instances.
	 */
	protected static $dispatchers = array();
	
	/**
	 * Retrieves a dispatcher for a project.
	 *
	 * @param      Project The project that governs the dispatcher.
	 *
	 * @return     YoudsFrameworkPhingEventDispatcher The dispatcher.
	 *
	 * @author     Noah Fontes <noah.fontes@bitextender.com>
	 * @since      1.0.0
	 */
	public static function get(Project $project)
	{
		$hash = spl_object_hash($project);
		
		if(!isset(self::$dispatchers[$hash])) {
			self::$dispatchers[$hash] = new YoudsFrameworkPhingEventDispatcher($project);
		}
		
		return self::$dispatchers[$hash];
	}
	
	/**
	 * Removes a dispatcher.
	 *
	 * @param      Project The project that governs the dispatcher.
	 *
	 * @return     boolean True if the dispatcher is successfully removed, false
	 *                     otherwise.
	 *
	 * @author     Noah Fontes <noah.fontes@bitextender.com>
	 * @since      1.0.0
	 */
	public static function remove(Project $project)
	{
		$hash = spl_object_hash($project);
		
		if(isset(self::$dispatchers[$hash])) {
			unset(self::$dispatchers[$hash]);
			return true;
		}
		
		return false;
	}
}

?>

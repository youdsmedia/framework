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
 * Base task for all YoudsFramework tasks.
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
abstract class YoudsFrameworkTask extends Task
{
	protected $quiet = false;
	protected static $bootstrapped = false;
	protected static $youdsBootstrapped = false;
	
	/**
	 * Initializes the task by bootstrapping YoudsFramework build components.
	 */
	public function init()
	{
		if(!class_exists('YoudsFrameworkBuild')) {
			require_once(__DIR__ . '/../../../../../youds/build.php');
			YoudsFrameworkBuild::bootstrap();
		}
	}
	
	/**
	 * Sets whether log messages for this task will be suppressed.
	 *
	 * @param      bool Whether to suppressing log messages for this task.
	 */
	public function setQuiet($quiet)
	{
		$this->quiet = StringHelper::booleanValue($quiet);
	}
	
	/**
	 * Logs an event.
	 *
	 * @param      string The message to log.
	 * @param      int The priority of the message.
	 */
	public function log($message, $level = Project::MSG_INFO)
	{
		if($this->quiet === false) {
			parent::log($message, $level);
		}
	}
	
	/**
	 * Utility method to load YoudsFramework classes.
	 */
	protected function tryLoadYoudsFramework()
	{
		if(!class_exists('YoudsFramework')) {
			$sourceDirectory = (string)$this->project->getProperty('youds.directory.src');
			require_once($sourceDirectory . '/youds.php');
		}
	}
	
	/**
	 * Utility method to bootstrap YoudsFramework.
	 */
	protected function tryBootstrapYoudsFramework()
	{
		if(!self::$youdsBootstrapped) {
			/* Something might fuck up. We always use the template that you can
			 * actually read. */
			YoudsFrameworkConfig::set('exception.default_template',
				sprintf('%s/templates/plaintext.php', (string)$this->project->getProperty('youds.directory.src.exception')),
				$overwrite = true,
				$readonly = true
			);
			
			/* To further prevent fucking up, we force it into debug mode. */
			YoudsFrameworkConfig::set('core.debug', true, $overwrite = true, $readonly = true);
			
			require_once(
				sprintf('%s/%s/config.php',
					(string)$this->project->getProperty('project.directory'),
					(string)$this->project->getProperty('project.directory.app')
				)
			);
			YoudsFramework::bootstrap($this->project->getProperty('project.build.environment'));
			self::$youdsBootstrapped = true;
		}
	}
}

?>

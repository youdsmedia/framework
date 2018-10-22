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
 * YoudsFrameworkLogger provides an easy way to manage multiple log destinations and 
 * write to them all simultaneously.
 *
 * @package    youds
 * @subpackage logging
 *
 * @author     Sean Kerr <skerr@mojavi.org>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.9.0
 *
 * @version    $Id$
 */
class YoudsFrameworkLogger implements YoudsFrameworkILogger
{
	/**
	 * @var        array An array of YoudsFrameworkLoggerAppenders.
	 */
	protected $appenders = array();

	/**
	 * @var        int Logging level.
	 */
	protected $level = YoudsFrameworkLogger::WARN;

	/**
	 * Log a message.
	 *
	 * @param      YoudsFrameworkLoggerMessage A Message instance.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function log(YoudsFrameworkLoggerMessage $message)
	{
		// get message level
		$msgLevel = $message->getLevel();

		if($this->level & $msgLevel) {
			foreach($this->appenders as $appender) {
				$appender->write($message);
			}
		}
	}

	/**
	 * Set an appender.
	 *
	 * If an appender with the name already exists, an exception will be thrown.
	 *
	 * @param      string              An appender name.
	 * @param      YoudsFrameworkLoggerAppender An Appender instance.
	 *
	 * @throws     <b>YoudsFrameworkLoggingException</b> If an appender with the name 
	 *                                          already exists.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function setAppender($name, YoudsFrameworkLoggerAppender $appender)
	{
		if(!isset($this->appenders[$name])) {
			$this->appenders[$name] = $appender;
			return;
		}

		// appender already exists
		$error = 'An appender with the name "%s" is already registered';
		$error = sprintf($error, $name);
		throw new YoudsFrameworkLoggingException($error);
	}

	/**
	 * Returns a list of appenders for this logger.
	 *
	 * @return     array An associative array of appender names and instances.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getAppenders()
	{
		return $this->appenders;
	}

	/**
	 * Set the level.
	 *
	 * @param      int A log level.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function setLevel($level)
	{
		$this->level = $level;
	}

	/**
	 * Get the level.
	 *
	 * @author     Peter Limbach <peter.limbach@gmail.com>
	 * @since      1.1.0
	 */
	public function getLevel()
	{
		return $this->level;
	}

	/**
	 * Execute the shutdown procedure.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function shutdown()
	{
	}
}

?>

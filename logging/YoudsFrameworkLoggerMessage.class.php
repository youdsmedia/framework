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
 * YoudsFrameworkLoggerMessage, by default, holds a message and a priority level.
 * It is intended to be passed to a YoudsFrameworkLogger.
 *
 * @package    youds
 * @subpackage logging
 *
 * @author     Bob Zoller <bob@youds.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.10.0
 *
 * @version    $Id$
 */
class YoudsFrameworkLoggerMessage extends YoudsFrameworkParameterHolder
{
	/**
	 * Constructor.
	 *
	 * @param      string optional message
	 * @param      int    optional priority level
	 *
	 * @author     Bob Zoller <bob@youds.com>
	 * @since      0.10.0
	 */
	public function __construct($message = null, $level = YoudsFrameworkLogger::INFO)
	{
		$this->setParameter('message', $message);
		$this->setParameter('level', $level);
	}

	/**
	 * toString method.
	 *
	 * @return     string The message.
	 *
	 * @author     Bob Zoller <bob@youds.com>
	 * @since      0.10.0
	 */
	public function __toString()
	{
		return(is_array($this->getParameter('message')) ? implode("\n", $this->getParameter('message')) : (string) $this->getParameter('message'));
	}

	/**
	 * Set the message.
	 *
	 * @param      string The message to set.
	 *
	 * @return     YoudsFrameworkLoggerMessage
	 *
	 * @author     Bob Zoller <bob@youds.com>
	 * @since      0.10.0
	 */
	public function setMessage($message)
	{
		$this->setParameter('message', $message);
		return $this;
	}

	/**
	 * Append to the message.
	 *
	 * @param      string Message to append.
	 *
	 * @return     YoudsFrameworkLoggerMessage
	 *
	 * @author     Bob Zoller <bob@youds.com>
	 * @since      0.10.0
	 */
	public function appendMessage($message)
	{
		$this->appendParameter('message', $message);
		return $this;
	}

	/**
	 * Set the priority level.
	 *
	 * @param      int The priority level.
	 *
	 * @return     YoudsFrameworkLoggerMessage
	 *
	 * @author     Bob Zoller <bob@youds.com>
	 * @since      0.10.0
	 */
	public function setLevel($level)
	{
		$this->setParameter('level', $level);
		return $this;
	}

	/**
	 * Get the priority level.
	 *
	 * @return     int The priority level.
	 *
	 * @author     Bob Zoller <bob@youds.com>
	 * @since      0.10.0
	 */
	public function getLevel()
	{
		return $this->getParameter('level');
	}

	/**
	 * Get the message.
	 *
	 * @return     mixed The message.
	 *
	 * @author     Bob Zoller <bob@youds.com>
	 * @since      0.10.0
	 */
	public function getMessage()
	{
		return $this->getParameter('message');
	}
}

?>

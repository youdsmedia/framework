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
 * Represents an event that occurred within a Phing target.
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
class YoudsFrameworkPhingMessageEvent extends YoudsFrameworkPhingEvent
{
	/**
	 * @var        string The message of the event.
	 */
	protected $message = null;
	
	/**
	 * @var        int The priority of the event.
	 */
	protected $priority = null;
	
	/**
	 * Sets the event message.
	 *
	 * @param      string The message.
	 *
	 * @author     Noah Fontes <noah.fontes@bitextender.com>
	 * @since      1.0.0
	 */
	public function setMessage($message)
	{
		$this->message = (string)$message;
	}
	
	/**
	 * Gets the event message
	 *
	 * @return     string The message.
	 *
	 * @author     Noah Fontes <noah.fontes@bitextender.com>
	 * @since      1.0.0
	 */
	public function getMessage()
	{
		return $this->message;
	}
	
	/**
	 * Sets the event priority.
	 *
	 * @param      int The priority.
	 *
	 * @author     Noah Fontes <noah.fontes@bitextender.com>
	 * @since      1.0.0
	 */
	public function setPriority($priority)
	{
		$this->priority = (int)$priority;
	}
	
	/**
	 * Gets the event priority.
	 *
	 * @param      int The priority.
	 *
	 * @author     Noah Fontes <noah.fontes@bitextender.com>
	 * @since      1.0.0
	 */
	public function getPriority()
	{
		return $this->priority;
	}
}

?>

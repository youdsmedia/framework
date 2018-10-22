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
 * YoudsFrameworkTimestampLoggerLayout prepends the current date and time to the message.
 *
 * @package    youds
 * @subpackage logging
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.0
 *
 * @version    $Id$
 */
class YoudsFrameworkTimestampLoggerLayout extends YoudsFrameworkLoggerLayout
{
	/**
	 * Format a message.
	 *
	 * @param      YoudsFrameworkLoggerMessage An YoudsFrameworkLoggerMessage instance.
	 *
	 * @return     string A formatted message.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function format(YoudsFrameworkLoggerMessage $message)
	{
		return sprintf($this->getParameter('message_format', '[%1$s] %2$s'), strftime($this->getParameter('timestamp_format', '%c')), $message->__toString());
	}
}

?>

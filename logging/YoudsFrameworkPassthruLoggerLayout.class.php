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
 * YoudsFrameworkPassthruLoggerLayout is an YoudsFrameworkLoggerLayout that will return the entire
 * YoudsFrameworkLoggerMessage or parts of it, depending on the configuration.
 * 
 * Parameter "mode" controls the four possible modes of operation:
 *   'to_string' - return YoudsFrameworkLoggerMessage::__toString() (default)
 *   'full'      - return the full YoudsFrameworkLoggerMessage object
 *   'message'   - return YoudsFrameworkLoggerMessage::getMessage()
 *   'parameter' - return only one parameter of the object. By default, this is
 *                 "message"; can be changed using parameter "parameter".
 * Parameter "parameter" controls which parameter of the YoudsFrameworkLoggerMessage
 * object is used when "mode" is "parameter". Defaults to "message".
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
class YoudsFrameworkPassthruLoggerLayout extends YoudsFrameworkLoggerLayout
{
	/**
	 * Format a message.
	 *
	 * @param      YoudsFrameworkLoggerMessage An YoudsFrameworkLoggerMessage instance.
	 *
	 * @return     string A formatted message.
	 *
	 * @author     Bob Zoller <bob@youds.com>
	 * @since      0.10.0
	 */
	public function format(YoudsFrameworkLoggerMessage $message)
	{
		switch($this->getParameter('mode', 'to_string')) {
			case 'full':
				return $message;
			case 'message':
				return $message->getMessage();
			case 'parameter':
				return $message->getParameter($this->getParameter('parameter', 'message'));
			default:
				return $message->__toString();
		}
	}
}

?>

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
 * YoudsFrameworkJsonLoggerLayout is an YoudsFrameworkLoggerLayout that will return a JSON
 * representation of the YoudsFrameworkLoggerMessage or parts of it, depending on the
 * configuration.
 * 
 * Parameter "mode" controls the four possible modes of operation:
 *   'parameters' - serialize all parameters of the message
 *   'full'       - serialize the entire YoudsFrameworkLoggerMessage object
 *   'message'    - serialize the value of YoudsFrameworkLoggerMessage::getMessage()
 *   'parameter'  - serialize only one parameter of the object. By default, this
 *                  is "message"; can be changed using parameter "parameter".
 * Parameter "parameter" controls which parameter of the YoudsFrameworkLoggerMessage
 * object is used when "mode" is "parameter". Defaults to "message".
 *
 * @package    youds
 * @subpackage logging
 *
 * @author     David Zülke <david.zuelke@bitextender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      1.0.4
 *
 * @version    $Id$
 */
class YoudsFrameworkJsonLoggerLayout extends YoudsFrameworkLoggerLayout
{
	/**
	 * Format a message.
	 *
	 * @param      YoudsFrameworkLoggerMessage An YoudsFrameworkLoggerMessage instance.
	 *
	 * @return     string The YoudsFrameworkLoggerMessage object as a JSON-encoded string.
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      1.0.4
	 */
	public function format(YoudsFrameworkLoggerMessage $message)
	{
		switch($this->getParameter('mode', 'parameters')) {
			case 'full':
				$value = $message;
				break;
			case 'message':
				$value = $message->getMessage();
				break;
			case 'parameter':
				$value = $message->getParameter($this->getParameter('parameter', 'message'));
				break;
			default:
				$value = $message->getParameters();
		}
		
		return json_encode($value);
	}
}

?>

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
 * YoudsFrameworkStdoutLoggerAppender appends an YoudsFrameworkLoggerMessage to stdout.
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
class YoudsFrameworkStdoutLoggerAppender extends YoudsFrameworkStreamLoggerAppender
{
	/**
	 * Initialize the object.
	 *
	 * @param      YoudsFrameworkContext An YoudsFrameworkContext instance.
	 * @param      array        An associative array of initialization parameters.
	 *
	 * @author     Bob Zoller <bob@youds.com>
	 * @since      0.10.0
	 */
	public function initialize(YoudsFrameworkContext $context, array $parameters = array())
	{
		$parameters['destination'] = 'php://stdout';
		// 'a' doesn't work on Linux
		// http://bugs.php.net/bug.php?id=45303
		$parameters['mode'] = 'w';
		
		parent::initialize($context, $parameters);
	}
}

?>

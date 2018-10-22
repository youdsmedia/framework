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

require_once(__DIR__ . '/YoudsFrameworkBuildLogger.php');

/**
 * Logs events through YoudsFramework's default Phing logger, but ignores all proxy
 * names.
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
class YoudsFrameworkProxyBuildLogger extends YoudsFrameworkBuildLogger
{
	/**
	 * Logs the start of a target.
	 *
	 * The event is only logged if the target is not an instance of
	 * <code>YoudsFrameworkProxyTarget</code>.
	 *
	 * @param      BuildEvent An event containing the data to be logged.
	 *
	 * @see        YoudsFrameworkProxyTarget
	 * @see        DefaultLogger::targetStarted()
	 *
	 * @author     Noah Fontes <noah.fontes@bitextender.com>
	 * @since      1.0.0
	 */
	public function targetStarted(BuildEvent $event)
	{
		if(!$event->getTarget() instanceof YoudsFrameworkProxyTarget) {
			parent::targetStarted($event);
		}
	}
	
	/**
	 * Logs the end of a target.
	 *
	 * The event is only logged if the target is not an instance of
	 * <code>YoudsFrameworkProxyTarget</code>.
	 *
	 * @param      BuildEvent An event containing the data to be logged.
	 *
	 * @see        YoudsFrameworkProxyTarget
	 * @see        DefaultLogger::targetStarted()
	 *
	 * @author     Noah Fontes <noah.fontes@bitextender.com>
	 * @since      1.0.0
	 */
	public function targetFinished(BuildEvent $event)
	{
		if(!$event->getTarget() instanceof YoudsFrameworkProxyTarget) {
			parent::targetFinished($event);
		}
	}
}

?>

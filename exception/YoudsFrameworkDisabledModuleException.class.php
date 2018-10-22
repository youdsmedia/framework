<?php

// +---------------------------------------------------------------------------+
// | This file is part of the YoudsFramework package.                                   |
// | Copyright (c) 2005-2011 the YoudsFramework Project.                                |
// | Based on the Mojavi3 MVC Framework, Copyright (c) 2003-2005 Sean Kerr.    |
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
 * YoudsFrameworkDisabledModuleException is thrown when Controller::initializeModule
 * gets called on a disabled module
 *
 * @package    youds
 * @subpackage exception
 *
 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
 * @copyright  The YoudsFramework Project
 *
 * @since      1.0.0
 *
 * @version    $Id$
 */
class YoudsFrameworkDisabledModuleException extends YoudsFrameworkInitializationException
{
}

?>

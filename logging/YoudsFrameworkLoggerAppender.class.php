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
 * YoudsFrameworkLoggerAppender allows you to specify a destination for log data and 
 * provide a custom layout for it, through which all log messages will be 
 * formatted.
 *
 * @package    youds
 * @subpackage logging
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @author     Bob Zoller <bob@youds.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.10.0
 *
 * @version    $Id$
 */
abstract class YoudsFrameworkLoggerAppender extends YoudsFrameworkParameterHolder
{
	/**
	 * @var        YoudsFrameworkContext An YoudsFrameworkContext instance.
	 */
	protected $context = null;

	/**
	 * @var        YoudsFrameworkLoggerLayout An YoudsFrameworkLoggerLayout instance.
	 */
	protected $layout = null;

	/**
	 * Initialize the object.
	 *
	 * @param      YoudsFrameworkContext An YoudsFrameworkContext instance.
	 * @param      array        An associative array of initialization parameters.
	 *
	 * @author     Bob Zoller <bob@youds.com>
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.10.0
	 */
	public function initialize(YoudsFrameworkContext $context, array $parameters = array())
	{
		$this->context = $context;
		
		$this->setParameters($parameters);
	}

	/**
	 * Retrieve the current application context.
	 *
	 * @return     YoudsFrameworkContext An YoudsFrameworkContext instance.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.10.0
	 */
	public final function getContext()
	{
		return $this->context;
	}

	/**
	 * Retrieve the layout.
	 *
	 * @return     YoudsFrameworkLoggerLayout A Layout instance, if it has been set, 
	 *                               otherwise null.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function getLayout()
	{
		return $this->layout;
	}

	/**
	 * Set the layout.
	 *
	 * @param      YoudsFrameworkLoggerLayout A Layout instance.
	 *
	 * @return     YoudsFrameworkLoggerAppender
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function setLayout(YoudsFrameworkLoggerLayout $layout)
	{
		$this->layout = $layout;
		return $this;
	}

	/**
	 * Execute the shutdown procedure.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	abstract function shutdown();

	/**
	 * Write log data to this appender.
	 *
	 * @param      YoudsFrameworkLoggerMessage Log data to be written.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	abstract function write(YoudsFrameworkLoggerMessage $message);
}

?>

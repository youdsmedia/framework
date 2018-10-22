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
 * YoudsFrameworkLoggerLayout allows you to specify a message layout for log messages.
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
abstract class YoudsFrameworkLoggerLayout extends YoudsFrameworkParameterHolder
{
	/**
	 * @var        YoudsFrameworkContext An YoudsFrameworkContext instance.
	 */
	protected $context = null;

	/**
	 * @var        string A message layout.
	 */
	protected $layout = null;

	/**
	 * Initialize the Layout.
	 *
	 * @param      YoudsFrameworkContext An YoudsFrameworkContext instance.
	 * @param      array        An associative array of initialization parameters.
	 *
	 * @author     Veikko Mäkinen <mail@veikkomakinen.com>
	 * @since      0.10.0
	 */
	public function initialize(YoudsFrameworkContext $context, array $parameters = array())
	{
		$this->context = $context;
		$this->parameters = $parameters;
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
	 * Format a message.
	 *
	 * @param      YoudsFrameworkLoggerMessage A Message instance.
	 *
	 * @return     string A formatted message.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	abstract function format(YoudsFrameworkLoggerMessage $message);

	/**
	 * Retrieve the message layout.
	 *
	 * @return     string A message layout.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function getLayout()
	{
		return $this->layout;
	}

	/**
	 * Set the message layout.
	 *
	 * @param      string A message layout.
	 *
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function setLayout($layout)
	{
		$this->layout = $layout;
	}
}

?>

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
 * YoudsFrameworkFilter provides a way for you to intercept incoming requests or outgoing
 * responses.
 *
 * @package    youds
 * @subpackage filter
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.0
 *
 * @version    $Id$
 */
interface YoudsFrameworkIFilter
{
	/**
	 * Execute this filter.
	 *
	 * @param      YoudsFrameworkFilterChain A FilterChain instance.
	 * @param      YoudsFrameworkExecutionContainer The current execution container.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.11.0
	 */
	public function execute(YoudsFrameworkFilterChain $filterChain, YoudsFrameworkExecutionContainer $container);

	/**
	 * Retrieve the current application context.
	 *
	 * @return     YoudsFrameworkContext The current YoudsFrameworkContext instance.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.11.0
	 */
	public function getContext();

	/**
	 * Initialize this Filter.
	 *
	 * @param      YoudsFrameworkContext The current application context.
	 * @param      array        An associative array of initialization parameters.
	 *
	 * @throws     <b>YoudsFrameworkInitializationException</b> If an error occurs while
	 *                                                 initializing this Filter.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.11.0
	 */
	public function initialize(YoudsFrameworkContext $context, array $parameters = array());
}

?>

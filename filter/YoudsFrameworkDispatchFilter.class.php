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
 * YoudsFrameworkDispatchFilter is the last in the chain of global filters and executes
 * the execution container, also re-setting the container's response to the
 * return value of the execution, so responses from forwards are passed along
 * properly.
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
class YoudsFrameworkDispatchFilter extends YoudsFrameworkFilter implements YoudsFrameworkIGlobalFilter
{
	/**
	 * Execute this filter.
	 *
	 * The DispatchFilter executes the execution container.
	 *
	 * @param      YoudsFrameworkFilterChain        The filter chain.
	 * @param      YoudsFrameworkExecutionContainer The current execution container.
	 *
	 * @throws     <b>YoudsFrameworkFilterException</b> If an error occurs during execution.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function execute(YoudsFrameworkFilterChain $filterChain, YoudsFrameworkExecutionContainer $container)
	{
		$container->setResponse($container->execute());
	}
}

?>

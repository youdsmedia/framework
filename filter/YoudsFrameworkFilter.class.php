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
 * YoudsFrameworkFilter provides a way for you to intercept incoming requests or outgoing
 * responses.
 *
 * @package    youds
 * @subpackage filter
 *
 * @author     Sean Kerr <skerr@mojavi.org>
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.9.0
 *
 * @version    $Id$
 */
abstract class YoudsFrameworkFilter extends YoudsFrameworkParameterHolder implements YoudsFrameworkIFilter
{
	/**
	 * @var        YoudsFrameworkContext An YoudsFrameworkContext instance.
	 */
	protected $context = null;

	/**
	 * Retrieve the current application context.
	 *
	 * @return     YoudsFrameworkContext The current Context instance.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public final function getContext()
	{
		return $this->context;
	}

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
	 * @since      0.9.0
	 */
	public function initialize(YoudsFrameworkContext $context, array $parameters = array())
	{
		$this->context = $context;

		$this->setParameters($parameters);
	}
	
	/**
	 * The default "execute" method, which just calls continues in the chain.
	 *
	 * @param      YoudsFrameworkFilterChain        A FilterChain instance.
	 * @param      YoudsFrameworkExecutionContainer The current execution container.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function execute(YoudsFrameworkFilterChain $filterChain, YoudsFrameworkExecutionContainer $container)
	{
		$filterChain->execute($container);
	}
}

?>

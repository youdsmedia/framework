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
 * A renderer produces the output as defined by a View
 *
 * @package    youds
 * @subpackage renderer
 *
 * @author     Felix Weis <mail@felixweis.com>
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.0
 *
 * @version    $Id$
 */
class YoudsFrameworkEzctemplateTemplate extends ezcTemplate implements YoudsFrameworkIEzctemplateTemplate
{
	/**
	 * @var        YoudsFrameworkContext The YoudsFramework Context.
	 */
	protected $context = null;
	
	/**
	 * Retrieve the current application context.
	 *
	 * @return     YoudsFrameworkContext The current YoudsFrameworkContext instance.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public final function getContext()
	{
		return $this->context;
	}
	
	/**
	 * Retrieve the current application context.
	 *
	 * @param      YoudsFrameworkContext The current YoudsFrameworkContext instance.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public final function setContext(YoudsFrameworkContext $context)
	{
		$this->context = $context;
	}
}

?>

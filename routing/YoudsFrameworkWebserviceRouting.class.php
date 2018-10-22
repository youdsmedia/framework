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
 * YoudsFrameworkWebserviceRouting handles the routing for Web Service requests.
 *
 * @package    youds
 * @subpackage routing
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.0
 *
 * @version    $Id$
 */
class YoudsFrameworkWebserviceRouting extends YoudsFrameworkRouting
{
	/**
	 * Initialize the routing instance.
	 *
	 * @param      YoudsFrameworkContext A Context instance.
	 * @param      array        An array of initialization parameters.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function initialize(YoudsFrameworkContext $context, array $parameters = array())
	{
		parent::initialize($context, $parameters);
		
		if(!$this->isEnabled()) {
			return;
		}
		
		$this->updateInput();
	}
	
	/**
	 * Set the name of the called web service method as the routing input.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function updateInput()
	{
		$this->input = $this->context->getRequest()->getInvokedMethod();
	}
}

?>

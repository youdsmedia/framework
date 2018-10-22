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
 * YoudsFrameworkConsoleRouting handles the routing for command line requests.
 *
 * @package    youds
 * @subpackage routing
 *
 * @author     David Zülke <david.zuelke@bitextender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      1.0.0
 *
 * @version    $Id$
 */
class YoudsFrameworkConsoleRouting extends YoudsFrameworkRouting
{
	/**
	 * Initialize the routing instance.
	 *
	 * @param      YoudsFrameworkContext A Context instance.
	 * @param      array        An array of initialization parameters.
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      1.0.0
	 */
	public function initialize(YoudsFrameworkContext $context, array $parameters = array())
	{
		parent::initialize($context, $parameters);
		
		if(!$this->isEnabled()) {
			return;
		}
	}
	
	/**
	 * Set the name of the called web service method as the routing input.
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      1.0.0
	 */
	public function startup()
	{
		parent::startup();
		
		$this->input = $this->context->getRequest()->getInput();
	}
}

?>

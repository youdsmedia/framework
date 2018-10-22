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
 * YoudsFrameworkXmlConfigHandler is the base config handler that deals with DOMDocuments
 *
 * @package    youds
 * @subpackage config
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.0
 *
 * @version    $Id$
 */
abstract class YoudsFrameworkXmlConfigHandler extends YoudsFrameworkBaseConfigHandler implements YoudsFrameworkIXmlConfigHandler
{
	/**
	 * @var        YoudsFrameworkContext The context to work with (if available).
	 */
	protected $context = null;
	
	/**
	 * Initialize this ConfigHandler.
	 *
	 * @param      YoudsFrameworkContext The context to work with (if available).
	 * @param      array        An associative array of initialization parameters.
	 *
	 * @throws     <b>YoudsFrameworkInitializationException</b> If an error occurs while
	 *                                                 initializing the
	 *                                                 ConfigHandler
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function initialize(YoudsFrameworkContext $context = null, $parameters = array())
	{
		$this->context = $context;
		$this->setParameters($parameters);
	}
}

?>

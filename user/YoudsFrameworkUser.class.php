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
 * YoudsFrameworkUser wraps a client session and provides accessor methods for user
 * attributes. It also makes storing and retrieving multiple page form data
 * rather easy by allowing user attributes to be stored in namespaces, which
 * help organize data.
 *
 * @package    youds
 * @subpackage user
 *
 * @author     Sean Kerr <skerr@mojavi.org>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.9.0
 *
 * @version    $Id$
 */
class YoudsFrameworkUser extends YoudsFrameworkAttributeHolder
{
	/**
	 * @var        YoudsFrameworkContext An YoudsFrameworkContext instance.
	 */
	protected $context = null;

	/**
	 * @var        string Storage namespace where user attributes are put.
	 */
	protected $storageNamespace = 'org.youds.user.User';

	/**
	 * Retrieve the current application context.
	 *
	 * @return     YoudsFrameworkContext An YoudsFrameworkContext instance.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public final function getContext()
	{
		return $this->context;
	}

	/**
	 * Retrieve the Storage namespace
	 *
	 * @return     string The Storage namespace
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getStorageNamespace()
	{
		return $this->storageNamespace;
	}

	/**
	 * Initialize this User.
	 *
	 * @param      YoudsFrameworkContext An YoudsFrameworkContext instance.
	 * @param      array        An associative array of initialization parameters.
	 *
	 * @throws     <b>YoudsFrameworkInitializationException</b> If an error occurs while
	 *                                                 initializing this User.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function initialize(YoudsFrameworkContext $context, array $parameters = array())
	{
		$this->context = $context;

		if(isset($parameters['default_namespace'])) {
			$this->defaultNamespace = $parameters['default_namespace'];
		}
		
		if(isset($parameters['storage_namespace'])) {
			$this->storageNamespace = $parameters['storage_namespace'];
		}

		$this->setParameters($parameters);
		
		// read data from storage
		$this->attributes = $context->getStorage()->read($this->storageNamespace);

		if($this->attributes == null) {
			// initialize our attributes array
			$this->attributes = array();
		}
	}

	/**
	 * Startup the user.
	 *
	 * You'd usually try to auth from a cookie here etc.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function startup()
	{
	}

	/**
	 * Execute the shutdown procedure.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function shutdown()
	{
		// write attributes to the storage
		$this->getContext()->getStorage()->write($this->storageNamespace, $this->attributes);
	}
}

?>

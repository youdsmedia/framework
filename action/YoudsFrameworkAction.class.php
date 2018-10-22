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
 * YoudsFrameworkAction allows you to separate application and business logic from your
 * presentation. By providing a core set of methods used by the framework,
 * automation in the form of security and validation can occur.
 *
 * @package    youds
 * @subpackage action
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
abstract class YoudsFrameworkAction
{
	/**
	 * @var        YoudsFrameworkExecutionContainer This action's execution container.
	 */
	protected $container = null;

	/**
	 * @var        YoudsFrameworkContext An YoudsFrameworkContext instance.
	 */
	protected $context = null;

	/**
	 * Retrieve the current application context.
	 *
	 * @return     YoudsFrameworkContext The current YoudsFrameworkContext instance.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public final function getContext()
	{
		return $this->context;
	}

	/**
	 * Retrieve the execution container for this action.
	 *
	 * @return     YoudsFrameworkExecutionContainer This action's execution container.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public final function getContainer()
	{
		return $this->container;
	}

	/**
	 * Retrieve the credential required to access this action.
	 *
	 * @return     mixed Data that indicates the level of security for this
	 *                   action.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function getCredentials()
	{
		return null;
	}

	/**
	 * Execute any post-validation error application logic.
	 *
	 * @param      YoudsFrameworkRequestDataHolder The action's request data holder.
	 *
	 * @return     mixed A string containing the view name associated with this
	 *                   action.
	 *                   Or an array with the following indices:
	 *                   - The parent module of the view that will be executed.
	 *                   - The view that will be executed.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function handleError(YoudsFrameworkRequestDataHolder $rd)
	{
		return 'Error';
	}

	/**
	 * Initialize this action.
	 *
	 * @param      YoudsFrameworkExecutionContainer This Action's execution container.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function initialize(YoudsFrameworkExecutionContainer $container)
	{
		$this->container = $container;

		$this->context = $container->getContext();
	}

	/**
	 * Indicates that this action requires security.
	 *
	 * @return     bool true, if this action requires security, otherwise false.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function isSecure()
	{
		return false;
	}

	/**
	 * Whether or not this action is "simple", i.e. doesn't use validation etc.
	 *
	 * @return     bool true, if this action should act in simple mode, or false.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function isSimple()
	{
		return false;
	}

	/**
	 * Manually register validators for this action.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function registerValidators()
	{
	}

	/**
	 * Manually validate files and parameters.
	 *
	 * @param      YoudsFrameworkRequestDataHolder The action's request data holder.
	 *
	 * @return     bool true, if validation completed successfully, otherwise
	 *                  false.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function validate(YoudsFrameworkRequestDataHolder $rd)
	{
		return true;
	}

	/**
	 * Get the default View name if this Action doesn't serve the Request method.
	 *
	 * @return     mixed A string containing the view name associated with this
	 *                   action.
	 *                   Or an array with the following indices:
	 *                   - The parent module of the view that will be executed.
	 *                   - The view that will be executed.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getDefaultViewName()
	{
		return 'Input';
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::clearAttributes()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function clearAttributes()
	{
		$this->container->clearAttributes();
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::getAttribute()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function &getAttribute($name, $default = null)
	{
		return $this->container->getAttribute($name, null, $default);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::getAttributeNames()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function getAttributeNames()
	{
		return $this->container->getAttributeNames();
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::getAttributes()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function &getAttributes()
	{
		return $this->container->getAttributes();
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::hasAttribute()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function hasAttribute($name)
	{
		return $this->container->hasAttribute($name);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::removeAttribute()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function &removeAttribute($name)
	{
		return $this->container->removeAttribute($name);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::setAttribute()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function setAttribute($name, $value)
	{
		$this->container->setAttribute($name, $value);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::appendAttribute()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.10.0
	 */
	public function appendAttribute($name, $value)
	{
		$this->container->appendAttribute($name, $value);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::setAttributeByRef()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function setAttributeByRef($name, &$value)
	{
		$this->container->setAttributeByRef($name, $value);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::appendAttributeByRef()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.10.0
	 */
	public function appendAttributeByRef($name, &$value)
	{
		$this->container->appendAttributeByRef($name, $value);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::setAttributes()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function setAttributes(array $attributes)
	{
		$this->container->setAttributes($attributes);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::setAttributesByRef()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function setAttributesByRef(array &$attributes)
	{
		$this->container->setAttributesByRef($attributes);
	}
}

?>

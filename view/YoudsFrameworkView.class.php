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
 * A view represents the presentation layer of an action. Output can be
 * customized by supplying attributes, which a template can manipulate and
 * display.
 *
 * @package    youds
 * @subpackage view
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
abstract class YoudsFrameworkView
{
	/**
	 * @since      0.9.0
	 */
	const NONE = null;

	/**
	 * @var        YoudsFrameworkExecutionContainer This view's execution container.
	 */
	protected $container = null;

	/**
	 * @var        YoudsFrameworkContext The YoudsFrameworkContext instance this View belongs to.
	 */
	protected $context = null;

	/**
	 * @var        array An array of defined layers.
	 */
	protected $layers = array();

	/**
	 * Execute any presentation logic and set template attributes.
	 *
	 * @param      YoudsFrameworkRequestDataHolder The action's request data holder.
	 *
	 * @return     YoudsFrameworkExecutionContainer An array of forwarding information in
	 *                                     case a forward should occur, or null.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	abstract function execute(YoudsFrameworkRequestDataHolder $rd);

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
	 * Retrieve the Response instance for this View.
	 *
	 * @return     YoudsFrameworkResponse The Response instance.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public final function getResponse()
	{
		return $this->container->getResponse();
	}

	/**
	 * Initialize this view.
	 *
	 * @param      YoudsFrameworkExecutionContainer This View's execution container.
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
	 * Create a new template layer object.
	 *
	 * This will automatically set the name of the layer, the current module, the
	 * current view name as the template, and the output type name.
	 *
	 * @param      string The class name of the YoudsFrameworkTemplateLayer implementation.
	 * @param      string The name of the layer.
	 * @param      mixed  An optional name of the non-default renderer to use, or
	 *                    an YoudsFrameworkRenderer instance to use.
	 *
	 * @return     YoudsFrameworkTemplateLayer A template layer instance.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function createLayer($class, $name, $renderer = null)
	{
		$layer = new $class();
		if(!is_subclass_of($layer, 'YoudsFrameworkTemplateLayer')) {
			throw new YoudsFrameworkViewException('Class "$class" is not a subclass of YoudsFrameworkTemplateLayer');
		}
		$layer->initialize($this->context, array('name' => $name, 'module' => $this->container->getViewModuleName(), 'template' => $this->container->getViewName(), 'output_type' => $this->container->getOutputType()->getName()));
		if($renderer instanceof YoudsFrameworkRenderer) {
			$layer->setRenderer($renderer);
		} else {
			$layer->setRenderer($this->container->getOutputType()->getRenderer($renderer));
		}
		return $layer;
	}

	/**
	 * Append a layer to the list of layers.
	 *
	 * If no reference layer is given, the layer will be added to the end of the
	 * list.
	 *
	 * @param      YoudsFrameworkTemplateLayer The layer to insert.
	 * @param      YoudsFrameworkTemplateLayer An optional other layer to insert after.
	 *
	 * @return     YoudsFrameworkTemplateLayer The template layer that was inserted.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function appendLayer(YoudsFrameworkTemplateLayer $layer, YoudsFrameworkTemplateLayer $otherLayer = null)
	{
		if($otherLayer !== null && !in_array($otherLayer, $this->layers, true)) {
			throw new YoudsFrameworkViewException('Layer "' . $otherLayer->getName() . '" not in list');
		}

		if(($pos = array_search($layer, $this->layers, true)) !== false) {
			// given layer is already in the list, so we remove it first
			array_splice($this->layers, $pos, 1);
		}

		if($otherLayer === null) {
			$dest = count($this->layers);
		} elseif($otherLayer === $layer) {
			$dest = $pos;
		} else {
			$dest = array_search($otherLayer, $this->layers, true) + 1;
		}
		array_splice($this->layers, $dest, 0, array($layer));

		return $layer;
	}

	/**
	 * Prepend a layer to the list of layers.
	 *
	 * If no reference layer is given, the layer will be added to the beginning of
	 * the list.
	 *
	 * @param      YoudsFrameworkTemplateLayer The layer to insert.
	 * @param      YoudsFrameworkTemplateLayer An optional other layer to insert before.
	 *
	 * @return     YoudsFrameworkTemplateLayer The template layer that was inserted.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function prependLayer(YoudsFrameworkTemplateLayer $layer, YoudsFrameworkTemplateLayer $otherLayer = null)
	{
		if($otherLayer !== null && !in_array($otherLayer, $this->layers, true)) {
			throw new YoudsFrameworkViewException('Layer "' . $otherLayer->getName() . '" not in list');
		}

		if(($pos = array_search($layer, $this->layers, true)) !== false) {
			// given layer is already in the list, so we remove it first
			array_splice($this->layers, $pos, 1);
		}

		if($otherLayer === null) {
			$dest = 0;
		} elseif($otherLayer === $layer) {
			$dest = $pos;
		} else {
			$dest = array_search($otherLayer, $this->layers, true);
		}
		array_splice($this->layers, $dest, 0, array($layer));

		return $layer;
	}

	/**
	 * Remove a layer from the list.
	 *
	 * @param      YoudsFrameworkTemplateLayer The layer to remove.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function removeLayer(YoudsFrameworkTemplateLayer $layer)
	{
		if(($pos = array_search($layer, $this->layers, true)) === false) {
			throw new YoudsFrameworkViewException('Layer "' . $layer->getName() . '" not in list');
		}
		array_splice($this->layers, $pos, 1);
	}

	/**
	 * Remove all layers from the list.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function clearLayers()
	{
		$this->layers = array();
	}

	/**
	 * Retrieve a layer from the list.
	 *
	 * @param      string The name of the layer.
	 *
	 * @return     YoudsFrameworkTemplateLayer The layer instance, or null if not found.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getLayer($name)
	{
		foreach($this->layers as $layer) {
			if($name == $layer->getName()) {
				return $layer;
			}
		}
	}

	/**
	 * Get all layers from the list.
	 *
	 * @return     array An array of template layer instances.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getLayers()
	{
		return $this->layers;
	}

	/**
	 * Load a pre-configured layout.
	 *
	 * If no layout name is given, the default layout will be used.
	 *
	 * @param      string The (optional) name of the layout.
	 *
	 * @return     array An array of parameters set for the layout.
	 *
	 * @throws     YoudsFrameworkException If the layout doesn't exist.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function loadLayout($layoutName = null)
	{
		$layout = $this->container->getOutputType()->getLayout($layoutName);

		$this->clearLayers();

		foreach($layout['layers'] as $name => $layer) {
			$l = $this->createLayer($layer['class'], $name, $layer['renderer']);
			$l->setParameters($layer['parameters']);
			foreach($layer['slots'] as $slotName => $slot) {
				$l->setSlot($slotName, $this->createSlotContainer($slot['module'], $slot['action'], $slot['parameters'], $slot['output_type'], $slot['request_method']));
			}
			$this->appendLayer($l);
		}
		
		return $layout['parameters'];
	}

	/**
	 * Creates a new container with the same output type and request method as
	 * this view's container.
	 *
	 * This container will have a parameter called 'is_slot' set to true.
	 *
	 * @param      string The name of the module.
	 * @param      string The name of the action.
	 * @param      mixed  An YoudsFrameworkRequestDataHolder instance with additional
	 *                    request arguments or an array of request parameters.
	 * @param      string Optional name of an initial output type to set.
	 * @param      string Optional name of the request method to be used in this
	 *                    container.
	 *
	 * @return     YoudsFrameworkExecutionContainer A new execution container instance,
	 *                                     fully initialized.
	 *
	 * @see        YoudsFrameworkExecutionContainer::createExecutionContainer()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function createSlotContainer($moduleName, $actionName, $arguments = null, $outputType = null, $requestMethod = null)
	{
		if($arguments !== null && !($arguments instanceof YoudsFrameworkRequestDataHolder)) {
			$rdhc = $this->context->getRequest()->getParameter('request_data_holder_class');
			$arguments = new $rdhc(array(YoudsFrameworkRequestDataHolder::SOURCE_PARAMETERS => $arguments));
		}
		$container = $this->container->createExecutionContainer($moduleName, $actionName, $arguments, $outputType, $requestMethod);
		$container->setParameter('is_slot', true);
		// just in case it was carried over by YoudsFrameworkContainer::createExecutionContainer()
		$container->removeParameter('is_forward');
		return $container;
	}

	/**
	 * Creates a new container with the same output type and request method as
	 * this view's container.
	 *
	 * This container will have a parameter called 'is_forward' set to true.
	 *
	 * @param      string The name of the module.
	 * @param      string The name of the action.
	 * @param      mixed  An YoudsFrameworkRequestDataHolder instance with additional
	 *                    request arguments or an array of request parameters.
	 * @param      string Optional name of an initial output type to set.
	 * @param      string Optional name of the request method to be used in this
	 *                    container.
	 *
	 * @return     YoudsFrameworkExecutionContainer A new execution container instance,
	 *                                     fully initialized.
	 *
	 * @see        YoudsFrameworkExecutionContainer::createExecutionContainer()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function createForwardContainer($moduleName, $actionName, $arguments = null, $outputType = null, $requestMethod = null)
	{
		if($arguments !== null) {
			if(!($arguments instanceof YoudsFrameworkRequestDataHolder)) {
				$rdhc = $this->context->getRequest()->getParameter('request_data_holder_class');
				$arguments = new $rdhc(array(YoudsFrameworkRequestDataHolder::SOURCE_PARAMETERS => $arguments));
			}
		} else {
			// we carry over our container's arguments
			$arguments = $this->container->getArguments();
		}
		$container = $this->container->createExecutionContainer($moduleName, $actionName, $arguments, $outputType, $requestMethod);
		$container->setParameter('is_forward', true);
		return $container;
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::setAttributesByRef()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function clearAttributes()
	{
		$this->container->clearAttributes();
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::setAttributesByRef()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function &getAttribute($name, $default = null)
	{
		return $this->container->getAttribute($name, null, $default);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::setAttributesByRef()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function getAttributeNames()
	{
		return $this->container->getAttributeNames();
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::setAttributesByRef()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function &getAttributes()
	{
		return $this->container->getAttributes();
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::setAttributesByRef()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function hasAttribute($name)
	{
		return $this->container->hasAttribute($name);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::setAttributesByRef()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function &removeAttribute($name)
	{
		return $this->container->removeAttribute($name);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::setAttributesByRef()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function setAttribute($name, $value)
	{
		$this->container->setAttribute($name, $value);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::setAttributesByRef()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.10.0
	 */
	public function appendAttribute($name, $value)
	{
		$this->container->appendAttribute($name, $value);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::setAttributesByRef()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function setAttributeByRef($name, &$value)
	{
		$this->container->setAttributeByRef($name, $value);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::setAttributesByRef()
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.10.0
	 */
	public function appendAttributeByRef($name, &$value)
	{
		$this->container->appendAttributeByRef($name, $value);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::setAttributesByRef()
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

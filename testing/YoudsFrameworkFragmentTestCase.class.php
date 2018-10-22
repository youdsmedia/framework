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
 * YoudsFrameworkFragmentTestCase is the base class for all fragment tests and provides
 * the necessary assertions
 * 
 * 
 * @package    youds
 * @subpackage testing
 *
 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
 * @copyright  The YoudsFramework Project
 *
 * @since      1.0.0
 *
 * @version    $Id$
 */
abstract class YoudsFrameworkFragmentTestCase extends YoudsFrameworkPhpUnitTestCase implements YoudsFrameworkIFragmentTestCase
{
	/**
	 * @var        string the name of the context to use, null for default context
	 */
	protected $contextName = null;
	
	/**
	 * @var        string the name of the action to test
	 */
	protected $actionName;
	
	/**
	 * @var        string the name of the module 
	 */
	protected $moduleName;
	
	/**
	 * @var        bool   the result of the validation process
	 */
	protected $validationSuccess;
	
	/**
	 * @var        YoudsFrameworkExecutionContainer the container to run the action in
	 */
	protected $container;


	/**
	 * Constructs a test case with the given name.
	 *
	 * @param  string $name
	 * @param  array  $data
	 * @param  string $dataName
	 */
	public function __construct($name = NULL, array $data = array(), $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
		$this->setRunTestInSeparateProcess(true);
	}
	
	
	/**
	 * creates a new YoudsFrameworkExecutionContainer for each test
	 * 
	 * @return void
	 * 
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	public function setUp()
	{
		$this->container = $this->createExecutionContainer();
	}
	
	
	/**
	 * unsets the YoudsFrameworkExecutionContainer after each test
	 * 
	 * @return void
	 * 
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	public function tearDown()
	{
		$this->container = null;
	}
	
	/**
	 * Return the context defined for this test (or the default one).
	 *
	 * @return     YoudsFrameworkContext The context instance defined for this test.
	 * 
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	public function getContext()
	{
		return YoudsFrameworkContext::getInstance($this->contextName);
	}
	
	/**
	 * normalizes a viewname according to the configured rules
	 * 
	 * Please do not use this method, it exists only for internal 
	 * purposes and will be removed ASAP. You have been warned
	 * 
	 * @param      string the short view name
	 * 
	 * @return     string the full view name
	 * 
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function normalizeViewName($shortName)
	{
		if($shortName !== YoudsFrameworkView::NONE) {
			$shortName = YoudsFrameworkToolkit::evaluateModuleDirective(
				$this->moduleName,
				'youds.view.name',
				array(
					'actionName' => $this->actionName,
					'viewName' => $shortName,
				)
			);
			$shortName = YoudsFrameworkToolkit::canonicalName($shortName);
		}
		
		return $shortName;
	}

	/**
	 * create an executionfilter for the test
	 * 
	 * the configured executionfilter class will be wrapped in a testing
	 * extension to provide advanced capabilities required for testing 
	 * only
	 * 
	 * @return     YoudsFrameworkExecutionFilter 
	 * 
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function createExecutionFilter()
	{
		$effi = $this->getContext()->getFactoryInfo('execution_filter');

		$wrapper_class = $effi['class'].'UnitTesting';

		//extend the original class to overwrite runAction, so that the containers request data is cloned
		if(!class_exists($wrapper_class)) {
			$code = sprintf('
class %1$s extends %2$s
{
	protected $validationResult = null;
	
	public function executeView(YoudsFrameworkExecutionContainer $container)
	{
		$container->initRequestData();
		return parent::executeView($container);
	}
}',
			$wrapper_class,
			$effi['class']);

			eval($code);
		}

		// create a new execution container with the wrapped class
		$filter = new $wrapper_class();
		$filter->initialize($this->getContext(), $effi['parameters']);
		return $filter;
	}

	/**
	 * create an YoudsFrameworkExecutionContainer for the test
	 * 
	 * the configured YoudsFrameworkExecutionContainer class will be wrapped in a testing
	 * extension to provide advanced capabilities required for testing 
	 * only
	 * 
	 * @return     YoudsFrameworkExecutionContainer 
	 * 
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function createExecutionContainer($arguments = null, $outputType = null, $requestMethod = null)
	{
		$context = $this->getContext();

		$ecfi = $context->getFactoryInfo('execution_container');
		$wrapper_class = $ecfi['class'].'UnitTesting';

		//extend the original class to add a setter for the action instance
		if(!class_exists($wrapper_class)) {
			$code = sprintf('
class %1$s extends %2$s
{

	public function setActionInstance(YoudsFrameworkAction $action)
	{
		$this->actionInstance = $action;
	}
	
	public function initRequestData()
	{
		parent::initRequestData();
	}
}',
			$wrapper_class,
			$ecfi['class']);

			eval($code);
		}
		
		$ecfi['class'] = $wrapper_class;
		$context->setFactoryInfo('execution_container', $ecfi);
		
		if(!($arguments instanceof YoudsFrameworkRequestDataHolder)) {
			$arguments = $this->createRequestDataHolder(array(YoudsFrameworkRequestDataHolder::SOURCE_PARAMETERS => $arguments));
		}
		// create a new execution container with the wrapped class
		$container = $context->getController()->createExecutionContainer($this->moduleName, $this->actionName, $arguments, $outputType, $requestMethod);
		
		return $container;
	}

	/**
	 * creates an Action instance and initializes it with this testcases
	 * container
	 * 
	 * @return     YoudsFrameworkAction
	 * 
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function createActionInstance()
	{
		$actionInstance = $this->getContext()->getController()->createActionInstance($this->moduleName, $this->actionName);
		$actionInstance->initialize($this->container);
		return $actionInstance;
	}
	
	/**
	 * create a requestDataHolder with the given arguments and type
	 * 
	 * arguments need to be passed in the way {@see YoudsFrameworkRequestDataHolder} accepts them
	 * 
	 * array(YoudsFrameworkRequestDataHolder::SOURCE_PARAMETERS => array('foo' => 'bar'))
	 * 
	 * if no type is passed, the default for the configured request class will be used
	 * 
	 * @param      array   a two-dimensional array with the arguments
	 * @param      string  the subclass of YoudsFrameworkRequestDataHolder to create
	 * 
	 * @return     YoudsFrameworkRequestDataHolder
	 * 
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function createRequestDataHolder(array $arguments = array(), $type = null)
	{
		if(null === $type) {
			$type = $this->getContext()->getRequest()->getParameter('request_data_holder_class', 'YoudsFrameworkRequestDataHolder');
		}
		
		$class = new $type($arguments);
		return $class;
	}
	
	
	/**
	 * assert that the exectionContainer has a given attribute with the expected value
	 * 
	 * @param      mixed   the expected attribute value
	 * @param      string  the attribute name
	 * @param      string  the attribute namespace
	 * @param      string  an optional message to display if the test fails
	 * @param      float   $delta
	 * @param      integer $maxDepth
	 * @param      boolean $canonicalizeEol
	 * 
	 * @see        PHPUnit_Framework_Assert::assertEquals()
	 * 
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function assertContainerAttributeEquals($expected, $attributeName, $namespace = null, $message = 'Failed asserting that the attribute <%1$s/%2$s> has the value <%3$s>', $delta = 0, $maxDepth = 10, $canonicalizeEol = false)
	{
		$this->assertEquals($expected, $this->container->getAttribute($attributeName, $namespace), sprintf($message, $namespace, $attributeName, $expected), $delta, $maxDepth, $canonicalizeEol);
	}
	
	/**
	 * assert that the exectionContainer has a given attribute 
	 * 
	 * @param      string  the attribute name
	 * @param      string  the attribute namespace
	 * @param      string  an optional message to display if the test fails
	 * 
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function assertContainerAttributeExists($attributeName, $namespace = null, $message = 'Failed asserting that the container has an attribute named <%1$s/%2$s>.')
	{
		$this->assertTrue($this->container->hasAttribute($attributeName, $namespace), sprintf($message, $namespace, $attributeName));
	}
	
	/* --- container delegates --- */

	/**
	 * @see        YoudsFrameworkExcutionContainer::setOutputType()
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function setOutputType(YoudsFrameworkOutputType $outputType)
	{
		$this->container->setOutputType($outputType);
	}

	/**
	 * @see        YoudsFrameworkRequest::setRequestData()
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function setRequestData(YoudsFrameworkRequestDataHolder $rd)
	{
		$this->container->setRequestData($rd);
	}
	
	/**
	 * @see        YoudsFrameworkExcutionContainer::setArguments()
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function setArguments(YoudsFrameworkRequestDataHolder $rd)
	{
		$this->container->setArguments($rd);
	}

	/**
	 * @see        YoudsFrameworkExcutionContainer::setRequestMethod()
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function setRequestMethod($method)
	{
		$this->container->setRequestMethod($method);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::clearAttributes()
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function clearAttributes()
	{
		$this->container->clearAttributes();
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::getAttribute()
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function &getAttribute($name, $default = null)
	{
		return $this->container->getAttribute($name, null, $default);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::getAttributeNames()
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function getAttributeNames()
	{
		return $this->container->getAttributeNames();
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::getAttributes()
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function &getAttributes()
	{
		return $this->container->getAttributes();
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::hasAttribute()
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function hasAttribute($name)
	{
		return $this->container->hasAttribute($name);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::removeAttribute()
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function &removeAttribute($name)
	{
		return $this->container->removeAttribute($name);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::setAttribute()
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function setAttribute($name, $value)
	{
		$this->container->setAttribute($name, $value);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::appendAttribute()
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function appendAttribute($name, $value)
	{
		$this->container->appendAttribute($name, $value);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::setAttributesByRef()
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function setAttributeByRef($name, &$value)
	{
		$this->container->setAttributeByRef($name, $value);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::appendAttributeByRef()
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function appendAttributeByRef($name, &$value)
	{
		$this->container->appendAttributeByRef($name, $value);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::setAttributes()
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function setAttributes(array $attributes)
	{
		$this->container->setAttributes($attributes);
	}

	/**
	 * @see        YoudsFrameworkAttributeHolder::setAttributesByRef()
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function setAttributesByRef(array &$attributes)
	{
		$this->container->setAttributesByRef($attributes);
	}
}

?>

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
 * YoudsFrameworkViewTestCase is the base class for all view testcases and provides
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
abstract class YoudsFrameworkViewTestCase extends YoudsFrameworkFragmentTestCase
{
	/**
	 * @var        string the (short) name of the view
	 */
	protected $viewName;
	
	/**
	 * @var        mixed the result of the view execution
	 */
	protected $viewResult;
	
	/**
	 *  creates the view instance for this testcase
	 * 
	 * @return     YoudsFrameworkView
	 * 
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function createViewInstance()
	{
		$this->getContext()->getController()->initializeModule($this->moduleName);
		$viewName = $this->normalizeViewName($this->viewName);
		$viewInstance = $this->getContext()->getController()->createViewInstance($this->moduleName, $viewName);
		$viewInstance->initialize($this->container);
		return $viewInstance;
	}
	
	/**
	 *  runs the view instance for this testcase
	 * 
	 * @param      string the name of the output type to run the view for
	 *                    null for the default output type
	 * 
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function runView($otName = null)
	{
		$this->container->setActionInstance($this->createActionInstance());
		$this->container->setOutputType($this->getContext()->getController()->getOutputType($otName));
		$this->container->setViewInstance($this->createViewInstance());
		$executionFilter = $this->createExecutionFilter();
		$this->viewResult = $executionFilter->executeView($this->container);
	}
	
	/**
	 * assert that the view handles the given output type
	 * 
	 * @param      string  the output type name
	 * @param      boolean true if the generic 'execute' method should be accepted as handled
	 * @param      string  an optional message to display if the test fails
	 * 
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function assertHandlesOutputType($method, $acceptGeneric = false, $message = '')
	{
		$viewInstance = $this->createViewInstance();
		$constraint = new YoudsFrameworkConstraintViewHandlesOutputType($viewInstance, $acceptGeneric);
		
		self::assertThat($method, $constraint, $message);
	}
	
	/**
	 * assert that the view does not handle the given output type
	 * 
	 * @param      string  the output type name
	 * @param      boolean true if the generic 'execute' method should be accepted as handled
	 * @param      string  an optional message to display if the test fails
	 * 
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function assertNotHandlesOutputType($method, $acceptGeneric = false, $message = '')
	{
		$viewInstance = $this->createViewInstance();
		$constraint = self::logicalNot(new YoudsFrameworkConstraintViewHandlesOutputType($viewInstance, $acceptGeneric));
		
		self::assertThat($method, $constraint, $message);
	}
	
	/**
	 * assert that the response contains a redirect
	 * 
	 * @param      string the message to emit on failure
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function assertViewRedirects($message = 'Failed asserting that the view redirects')
	{
		$response = $this->container->getResponse();
		try {
			$this->assertTrue($response->hasRedirect(), $message);
		} catch (YoudsFrameworkException $e) {
			$this->fail($message);
		}
	}
	
	/**
	 * assert that the response contains no redirect
	 * 
	 * @param      string the message to emit on failure
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function assertViewRedirectsNot($message = 'Failed asserting that the view does not redirect')
	{
		$response = $this->container->getResponse();
		try {
			$this->assertFalse($response->hasRedirect(), $message);
		} catch (YoudsFrameworkException $e) {
			$this->fail($message);
		}
	}
	
	/**
	 * assert that the response contains the expected redirect
	 * 
	 * @param      mixed  the expected redirect
	 * @param      string the message to emit on failure
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function assertViewRedirectsTo($expected, $message = 'Failed asserting that the view redirects to the given target.')
	{
		$response = $this->container->getResponse();
		try {
			$this->assertEquals($expected, $response->getRedirect(), $message);
		} catch (YoudsFrameworkException $e) {
			$this->fail($message);
		}
	}
	
	/**
	 * Assert that the view sets the given content type.
	 * 
	 * this assertion only works on YoudsFrameworkWebResponse or subclasses
	 * 
	 * @param      string the expected content type
	 * @param      string the message to emit on failure
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function assertViewSetsContentType($expected, $message = 'Failed asserting that the view sets the content type "%1$s".')
	{
		$response = $this->container->getResponse();
		
		if(!($response instanceof YoudsFrameworkWebResponse)) {
			$this->fail(sprintf($message . ' (response is not an YoudsFrameworkWebResponse)', $expected));
		}
		$this->assertEquals($expected, $response->getContentType(), sprintf($message, $expected));
	}
	
	/**
	 * Assert that the view sets the given header with the given value.
	 * 
	 * this response only works on YoudsFrameworkWebResponse and subclasses
	 * 
	 * @param      string the name of the expected header
	 * @param      string the value of the expected header
	 * @param      string the message to emit on failure
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function assertViewSetsHeader($expected, $expectedValue = null, $message = 'Failed asserting that the view sets a header named <%1$s> with the value <%2$s>')
	{
		$response = $this->container->getResponse();
		
		if(!($response instanceof YoudsFrameworkWebResponse)) {
			$this->fail(sprintf($message . ' (response is not an YoudsFrameworkWebResponse)', $expected));
		}
		$this->assertEquals($expectedValue, $response->getHttpHeader($expected), sprintf($message, $expected, $expectedValue));
	}
	
	/**
	 * Assert that the view sets the given cookie with the given value.<y></y>
	 * 
	 * this response only works on YoudsFrameworkWebResponse and subclasses
	 * 
	 * @param      string the name of the expected cookie
	 * @param      string the value of the expected header
	 * @param      string the message to emit on failure
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function assertViewSetsCookie($expected, $expectedValue = null, $message = 'Failed asserting that the view sets a cookie named <%1$s> with a value of <%2$s>')
	{
		$response = $this->container->getResponse();
		
		if(!($response instanceof YoudsFrameworkWebResponse)) {
			$this->fail(sprintf($message . ' (response is not an YoudsFrameworkWebResponse)', $expected, var_export($expectedValue, true)));
		}
		$this->assertEquals($expectedValue, $response->getCookie($expected), sprintf($message, $expected, var_export($expectedValue, true)));
	}
	
	/**
	 * assert that the response has the given http status
	 * 
	 * this assertion only works on YoudsFrameworkWebResponse or subclasses
	 * 
	 * @param      string the expected http status
	 * @param      string the message to emit on failure
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function assertViewResponseHasHTTPStatus($expected, $message = 'Failed asserting that the response status is %1$s.')
	{
		$response = $this->container->getResponse();
		
		if(!($response instanceof YoudsFrameworkWebResponse)) {
			$this->fail(sprintf($message . ' (response is not an YoudsFrameworkWebResponse)', $expected));
		}
		$this->assertEquals($expected, $response->getHttpStatusCode(), sprintf($message, $expected));
	}
	
	/**
	 * assert that the response has the given content 
	 * 
	 * @param      mixed the expected content
	 * @param      string the message to emit on failure
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function assertViewResponseHasContent($expected, $message = 'Failed asserting that the response has content <%1$s>.')
	{
		$response = $this->container->getResponse();
		$this->assertEquals($expected, $response->getContent(), sprintf($message, $expected));
	}
	
	/**
	 * assert that the view result has the given content 
	 * 
	 * @param      mixed the expected content
	 * @param      string the message to emit on failure
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function assertViewResultEquals($expected, $message = 'Failed asserting the expected view result.')
	{
		$this->assertEquals($expected, $this->viewResult, sprintf($message, $expected));
	}
	
	/**
	 * assert that the view forwards to the given module/action
	 * 
	 * @param      string the expected module name
	 * @param      string the expected action name
	 * @param      string the message to emit on failure
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function assertViewForwards($expectedModule, $expectedAction, $message = 'Failed asserting that the view forwards to "%1$s" "%2$s".')
	{
		if(!($this->viewResult instanceof YoudsFrameworkExecutionContainer)) {
			$this->fail(sprintf($message, $expectedModule, $expectedAction));
		}
		$this->assertEquals($expectedModule, $this->viewResult->getModuleName());
		$this->assertEquals(YoudsFrameworkToolkit::canonicalName($expectedAction), $this->viewResult->getActionName());
	}
	
	/**
	 * assert that the view has the  given layer
	 * 
	 * @param      string the expected layer name
	 * @param      string the message to emit on failure
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected function assertHasLayer($expectedLayer, $message = 'Failed asserting that the view contains the layer "%1$s".')
	{
		$viewInstance = $this->container->getViewInstance();
		$layer = $viewInstance->getLayer($expectedLayer);
		
		if(null === $layer) {
			$this->fail(sprintf($message, $expectedLayer));
		}
	}
	
	/**
	 * assert that the view has the  given layer
	 * 
	 * @param      string the expected layer name
	 * @param      string the message to emit on failure
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      1.0.6
	 */
	protected function assertNotHasLayer($expectedLayer, $message = '')
	{
		$viewInstance = $this->container->getViewInstance();
		$layer = $viewInstance->getLayer($expectedLayer);
		
		if(null !== $layer) {
			$this->fail('Failed asserting that the view does not contain the layer.');
		}
	}
}

?>

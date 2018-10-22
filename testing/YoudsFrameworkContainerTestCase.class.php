<?php

// +---------------------------------------------------------------------------+
// | This file is part of the YoudsFramework package.                                   |
// | Copyright (c) 2005-2010 the YoudsFramework Project.                                |
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
 * YoudsFrameworkContainerTestCase is the base class for all tests that target a specific
 * container execution and provides the necessary assertions
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
 * @version    $Id: YoudsFrameworkFlowTestCase.class.php 3843 2009-02-16 14:12:47Z felix $
 */
abstract class YoudsFrameworkContainerTestCase extends YoudsFrameworkFragmentTestCase
{
	/**
	 * @var        string the name of the action to use
	 */
	protected $acionName;
	
	/**
	 * @var        string the name of the module the action resides in
	 */
	protected $moduleName;
	
	/**
	 * @var        YoudsFrameworkResponse the response after the dispatch call
	 */
	protected $response;
	
	/**
	 * dispatch the request
	 *
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0 
	 */
	public function execute($arguments = null, $outputType = null, $requestMethod = null)
	{
		$context = YoudsFrameworkContext::getInstance();
		
		$controller = $context->getController();
		$controller->setParameter('send_response', false);
		
		if(!($arguments instanceof YoudsFrameworkRequestDataHolder)) {
			$arguments = $this->createRequestDataHolder(array(YoudsFrameworkRequestDataHolder::SOURCE_PARAMETERS => $arguments));
		}
		
		$this->response = $controller->dispatch(null, $controller->createExecutionContainer($this->moduleName, $this->actionName, $arguments, $outputType, $requestMethod));
	}
	
	/**
	 * assert that the response has a given tag
	 * 
	 * @see the documentation of PHPUnit's assertTag()
	 * 
	 * @param      array the matcher describing the tag
	 * @param      string an optional message
	 * 
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	public function assertResponseHasTag($matcher, $message = '', $isHtml = true)
	{
		$this->assertTag($matcher, $this->response->getContent(), $message, $isHtml);
	}
	
	
	/**
	 * assert that the response does not have a given tag
	 * 
	 * @see the documentation of PHPUnit's assertTag()
	 * 
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	public function assertResponseHasNotTag($matcher, $message = '', $isHtml = true)
	{
		$this->assertNotTag($matcher, $this->response->getContent(), $message, $isHtml);
	}
}

?>

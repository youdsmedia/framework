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
 * YoudsFrameworkWebserviceRequest is the base class for Web Service requests
 *
 * @package    youds
 * @subpackage request
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.0
 *
 * @version    $Id$
 */
abstract class YoudsFrameworkWebserviceRequest extends YoudsFrameworkRequest
{
	/**
	 * @var        string The Input Data.
	 */
	protected $input = '';
	
	/**
	 * @var        string The method called by the web service request.
	 */
	protected $invokedMethod = '';
	
	/**
	 * Constructor.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setParameters(array(
			'request_data_holder_class' => 'YoudsFrameworkWebserviceRequestDataHolder',
		));
	}
	
	/**
	 * Initialize this Request.
	 *
	 * @param      YoudsFrameworkContext An YoudsFrameworkContext instance.
	 * @param      array        An associative array of initialization parameters.
	 *
	 * @throws     <b>YoudsFrameworkInitializationException</b> If an error occurs while
	 *                                                 initializing this Request.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function initialize(YoudsFrameworkContext $context, array $parameters = array())
	{
		// empty $_POST just to be sure
		$_POST = array();
		
		// grab the POST body
		$this->input = file_get_contents('php://input');
		
		parent::initialize($context, $parameters);
	}
	
	/**
	 * Get the input data, usually the request from the POST body.
	 *
	 * @return     string The input data.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getInput()
	{
		return $this->input;
	}
	
	/**
	 * Set the input data. Useful for debugging purposes.
	 *
	 * @param      string The input data.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function setInput($input)
	{
		$this->input = $input;
	}
	
	/**
	 * Set the name of the method called by the web service request.
	 *
	 * @return     string A method name.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function setInvokedMethod($method)
	{
		$this->invokedMethod = $method;
		
		// let the routing update its input
		$this->context->getRouting()->updateInput();
	}
	
	/**
	 * Get the name of the method called by the web service request.
	 *
	 * @return     string A method name.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getInvokedMethod()
	{
		return $this->invokedMethod;
	}
}

?>

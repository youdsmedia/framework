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
 * YoudsFrameworkSoapController handles SOAP requests.
 *
 * @package    youds
 * @subpackage controller
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.9.0
 *
 * @version    $Id$
 */
class YoudsFrameworkSoapController extends YoudsFrameworkController
{
	/**
	 * @param      YoudsFrameworkRequestDataHolder Additional request data for later use.
	 */
	protected $dispatchArguments = null;
	
	/**
	 * @param      YoudsFrameworkExecutionContainer Specific execution container to run.
	 */
	protected $dispatchContainer = null;
	
	/**
	 * @param      SoapClient The soap client instance we use to access WSDL info.
	 */
	protected $soapClient = null;
	
	/**
	 * @param      SoapServer The soap server instance that handles the request.
	 */
	protected $soapServer = null;
	
	/**
	 * Get the soap client instance we use to access WSDL info.
	 *
	 * @return     SoapClient The soap client instance.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getSoapClient()
	{
		return $this->soapClient;
	}
	
	/**
	 * Get the soap server instance we use to access WSDL info.
	 *
	 * @return     SoapServer The soap client instance.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getSoapServer()
	{
		return $this->soapServer;
	}
	
	/**
	 * Do any necessary startup work after initialization.
	 *
	 * This method is not called directly after initialize().
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function startup()
	{
		parent::startup();
		
		// user-supplied "wsdl" and "options" parameters
		$wsdl = $this->getParameter('wsdl');
		if(!$wsdl) {
			// no wsdl was specified, that means we generate one from the annotations in routing.xml
			$wsdl = $this->context->getRouting()->getWsdlPath();
		}
		$this->setParameter('wsdl', $wsdl);
		
		// get the name of the class to use for the client, defaults to PHP's own "SoapClient"
		$soapClientClass = $this->getParameter('soap_client_class', 'SoapClient');
		$soapClientOptions = $this->getParameter('soap_client_options', array());
		// get the name of the class to use for the server, defaults to PHP's own "SoapServer"
		$soapServerClass = $this->getParameter('soap_server_class', 'SoapServer');
		$soapServerOptions = $this->getParameter('soap_server_options', array());
		// get the name of the class to use for handling soap calls, defaults to YoudsFramework's "YoudsFrameworkSoapControllerCallHandler"
		$soapHandlerClass = $this->getParameter('soap_handler_class', 'YoudsFrameworkSoapControllerCallHandler');
		
		// force client's soap version to be the same as the server's
		if(isset($soapServerOptions['soap_version'])) {
			$soapClientOptions['soap_version'] = $soapServerOptions['soap_version'];
		}
		
		// force client's cache_wsdl setting to be the same as the server's
		if(isset($soapServerOptions['cache_wsdl'])) {
			// and cast it to an int
			$soapServerOptions['cache_wsdl'] = (int)$soapServerOptions['cache_wsdl'];
			$soapClientOptions['cache_wsdl'] = $soapServerOptions['cache_wsdl'];
		}
		
		if(isset($soapServerOptions['features'])) {
			// cast this to an int
			$soapServerOptions['features'] = (int)$soapServerOptions['features'];
		}
		
		// create a client, so we can grab the functions and types defined in the wsdl (not possible from the server, duh)
		$this->soapClient = new $soapClientClass($wsdl, $soapClientOptions);
		
		if($this->getParameter('auto_classmap')) {
			// we have to create a classmap automatically.
			// to do that, we read the defined types, and set identical values for type and class name.
			$classmap = array();
			
			// with an optional prefix, of course.
			$prefix = $this->getParameter('auto_classmap_prefix', '');
			
			foreach($this->soapClient->__getTypes() as $definition) {
				if(preg_match('/^struct (\S+) \{$/m', $definition, $matches)) {
					$classmap[$matches[1]] = $prefix . $matches[1];
				}
			}
			
			if(isset($soapServerOptions['classmap'])) {
				$classmap = array_merge((array) $classmap, $soapServerOptions['classmap']);
			}
			
			$soapServerOptions['classmap'] = $classmap;
		}
		
		// create a server
		$this->soapServer = new $soapServerClass($wsdl, $soapServerOptions);
		
		$newSoapHandlerClass = $soapHandlerClass . 'WithAutoHeaders';
		
		// build the special extension class to the handler that contains methods for each of the headers
		if($this->getParameter('auto_headers', true)) {
			// the cache filename we'll be using
			$cache = YoudsFrameworkConfigCache::getCacheName($soapHandlerClass, $this->context->getName());
			
			if(YoudsFrameworkConfigCache::isModified($wsdl, $cache)) {
				$doc = new DOMDocument();
				$doc->load($wsdl);
				$xpath = new DOMXPath($doc);
				$xpath->registerNamespace('soap', 'http://schemas.xmlsoap.org/wsdl/soap/');
				
				$code = array();
				
				$code[] = '<?php';
				$code[] = sprintf('class %s extends %s {', $newSoapHandlerClass, $soapHandlerClass);
				$code[] = '  protected $rd;';
				$code[] = '  public function __construct(YoudsFrameworkContext $context) {';
				$code[] = '    parent::__construct($context);';
				$code[] = '    $this->rd = $this->context->getRequest()->getRequestData();';
				$code[] = '  }';
				
				$headers = array();
				
				foreach($xpath->query('//soap:header') as $header) {
					$name = $header->getAttribute('part');
					
					if(in_array($name, $headers)) {
						continue;
					}
					$headers[] = $name;
					
					$code[] = sprintf('  public function %s($value) {', $name);
					$code[] = sprintf('    $this->rd->setHeader(%s, $value);', var_export($name, true));
					$code[] = '  }';
				}
				
				$code[] = '}';
				$code[] = '?>';
				
				$code = implode("\n", $code);
				
				YoudsFrameworkConfigCache::writeCacheFile($soapHandlerClass, $cache, $code);
			}
			
			include($cache);
		}
		
		// give it a class that handles method calls
		// that class uses __call
		// the class ctor gets the context as the first argument
		$this->soapServer->setClass($newSoapHandlerClass, $this->context);
	}
	/**
	 * Dispatch a request
	 *
	 * @param      YoudsFrameworkRequestDataHolder  An optional request data holder object
	 *                                     with additional request data.
	 * @param      YoudsFrameworkExecutionContainer An optional execution container that,
	 *                                     if given, will be executed right away,
	 *                                     skipping routing execution.
	 *
	 * @return     YoudsFrameworkResponse The response produced during this dispatch call.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function dispatch(YoudsFrameworkRequestDataHolder $arguments = null, YoudsFrameworkExecutionContainer $container = null)
	{
		// Remember The Milk... err... the arguments given.
		$this->dispatchArguments = $arguments;
		// and the container, too, if there was one
		$this->dispatchContainer = $container;
		
		// handle the request. the aforementioned __call will be run next
		// we use the input from the request as the argument, it contains the SOAP request
		// no need to send the response as SoapServer does that
		$this->soapServer->handle($this->context->getRequest()->getInput());
	}
	
	/**
	 * A method that is called in the __call overload by the SOAP call handler.
	 *
	 * All it does is call parent::dispatch() to prevent an infinite loop.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function doDispatch()
	{
		try {
			// return the content so SoapServer can send it.
			// YoudsFrameworkSoapResponse::send() does not send the content, but sets the headers on the SoapServer
			return parent::dispatch($this->dispatchArguments, $this->dispatchContainer);
		} catch(SoapFault $f) {
			$this->response->clear();
			$this->response->setContent($f);
			// return the content so SoapServer can send it.
			return $this->response;
		}
	}
}

?>

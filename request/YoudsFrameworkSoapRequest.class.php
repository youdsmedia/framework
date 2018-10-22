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
 * YoudsFrameworkSoapRequest is an implementation for handling SOAP Web Services using
 * PHP 5's SOAP extension.
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
class YoudsFrameworkSoapRequest extends YoudsFrameworkWebserviceRequest
{
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
			'request_data_holder_class' => 'YoudsFrameworkSoapRequestDataHolder',
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
	 * @author     Veikko Mäkinen <mail@veikkomakinen.com>
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function initialize(YoudsFrameworkContext $context, array $parameters = array())
	{
		parent::initialize($context, $parameters);
		
		$rdhc = $this->getParameter('request_data_holder_class');
		$this->setRequestData(new $rdhc(array(
			constant("$rdhc::SOURCE_PARAMETERS") => array(),
			constant("$rdhc::SOURCE_HEADERS") => array(),
		)));
		
		$this->setMethod($this->getParameter('default_method', 'read'));
	}
}

?>

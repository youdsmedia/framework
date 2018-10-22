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
 * YoudsFrameworkTestSuitesConfigHandler reads the testsuites configuration files to determine 
 * the available suites and their tests.
 *
 * @package    youds
 * @subpackage config
 *
 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      1.0.0
 *
 * @version    $Id$
 */
class YoudsFrameworkTestSuitesConfigHandler extends YoudsFrameworkXmlConfigHandler
{
	const XML_NAMESPACE = 'http://youds.com/youds/config/parts/testing/suites/1.1';
	
	/**
	 * Execute this configuration handler.
	 *
	 * @param      YoudsFrameworkXmlConfigDomDocument The document to parse.
	 *
	 * @return     string Data to be written to a cache file.
	 *
	 * @throws     <b>YoudsFrameworkParseException</b> If a requested configuration file is
	 *                                        improperly formatted.
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      0.9.0
	 */
	public function execute(YoudsFrameworkXmlConfigDomDocument $document)
	{
		// set up our default namespace
		$document->setDefaultNamespace(self::XML_NAMESPACE, 'suite');
		
		// remember the config file path
		$config = $document->documentURI;
		
		$data = array();
		// loop over <configuration> elements
		foreach($document->getConfigurationElements() as $configuration) {
			foreach($configuration->get('suites') as $current) {
				$includes = array();
				foreach($current->get('includes') as $include) {
					$includes[] = $include->textContent;
				}
				
				$excludes = array();
				foreach($current->get('excludes') as $exclude) {
					$excludes[] = $exclude->textContent;
				}
				
				$suite =  array(
					'class' => $current->getAttribute('class', 'YoudsFrameworkTestSuite'),
					'base' => $current->getAttribute('base', 'tests/'),
					'includes' => $includes,
					'excludes' => $excludes
				);
				
				$suite['testfiles'] = array();
				foreach($current->get('testfiles') as $file) {
					$suite['testfiles'][] = $file->textContent;
				}
				
				$data[$current->getAttribute('name')] = $suite;
			}
		}
		$code = 'return '.var_export($data, true);
		return $this->generate($code, $config);
	}
}

?>

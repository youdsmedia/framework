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
 * YoudsFrameworkFilterConfigHandler allows you to register filters with the system.
 *
 * @package    youds
 * @subpackage config
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @author     Sean Kerr <skerr@mojavi.org>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.9.0
 *
 * @version    $Id$
 */
class YoudsFrameworkFilterConfigHandler extends YoudsFrameworkXmlConfigHandler
{
	const XML_NAMESPACE = 'http://youds.com/youds/config/parts/filters/1.1';
	
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
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function execute(YoudsFrameworkXmlConfigDomDocument $document)
	{
		// set up our default namespace
		$document->setDefaultNamespace(self::XML_NAMESPACE, 'filters');
		
		$config = $document->documentURI;
		
		$filters = array();
		
		foreach($document->getConfigurationElements() as $cfg) {
			if($cfg->has('filters')) {
				foreach($cfg->get('filters') as $filter) {
					$name = $filter->getAttribute('name', YoudsFrameworkToolkit::uniqid());
					
					if(!isset($filters[$name])) {
						$filters[$name] = array('params' => array(), 'enabled' => YoudsFrameworkToolkit::literalize($filter->getAttribute('enabled', true)));
					} else {
						$filters[$name]['enabled'] = YoudsFrameworkToolkit::literalize($filter->getAttribute('enabled', $filters[$name]['enabled']));
					}
					
					if($filter->hasAttribute('class')) {
						$filters[$name]['class'] = $filter->getAttribute('class');
					}
					
					$filters[$name]['params'] = $filter->getYoudsFrameworkParameters($filters[$name]['params']);
				}
			}
		}
		
		$data = array();

		foreach($filters as $name => $filter) {
			if(stripos($name, 'youds') === 0) {
				throw new YoudsFrameworkConfigurationException('Filter names must not start with "youds".');
			}
			if(!isset($filter['class'])) {
				throw new YoudsFrameworkConfigurationException('No class name specified for filter "' . $name . '" in ' . $config);
			}
			if($filter['enabled']) {
				$rc = new ReflectionClass($filter['class']);
				$if = 'YoudsFrameworkI' . ucfirst(strtolower(substr(basename($config), 0, strpos(basename($config), '_filters')))) . 'Filter';
				if(!$rc->implementsInterface($if)) {
					throw new YoudsFrameworkFactoryException('Filter "' . $name . '" does not implement interface "' . $if . '"');
				}
				$data[] = '$filter = new ' . $filter['class'] . '();';
				$data[] = '$filter->initialize($this->context, ' . var_export($filter['params'], true) . ');';
				$data[] = '$filters[' . var_export($name, true) . '] = $filter;';
			}
		}

		return $this->generate($data, $config);
	}
}

?>

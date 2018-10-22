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
 * Template layer implementation for templates fetched using a PHP stream.
 *
 * @package    youds
 * @subpackage view
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.0
 *
 * @version    $Id$
 */
class YoudsFrameworkFileTemplateLayer extends YoudsFrameworkStreamTemplateLayer
{
	/**
	 * Constructor.
	 *
	 * @param      array Initial parameters.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function __construct(array $parameters = array())
	{
		$targets = array();
		if(YoudsFrameworkConfig::get('core.use_translation')) {
			$targets[] = '${directory}/${locale}/${template}${extension}';
			$targets[] = '${directory}/${template}.${locale}${extension}';
		}
		$targets[] = '${directory}/${template}${extension}';
		
		parent::__construct(array_merge(array(
			'directory' => YoudsFrameworkConfig::get('core.module_dir') . '/${module}/templates',
			'scheme' => 'file',
			'check' => true,
			'targets' => $targets,
		), $parameters));
	}
	
	/**
	 * Initialize the layer.
	 *
	 * Will try and figure out an alternative default for "directory".
	 *
	 * @param      YoudsFrameworkContext The current Context instance.
	 * @param      array        An array of initialization parameters.
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      1.0.0
	 */
	public function initialize(YoudsFrameworkContext $context, array $parameters = array())
	{
		$this->setParameter('directory', YoudsFrameworkToolkit::evaluateModuleDirective(isset($parameters['module']) ? $parameters['module'] : '', 'youds.template.directory'));
		
		parent::initialize($context, $parameters);
	}
	
	/**
	 * Get the full, resolved stream location name to the template resource.
	 *
	 * @return     string A PHP stream resource identifier.
	 *
	 * @throws     YoudsFrameworkException If the template could not be found.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getResourceStreamIdentifier()
	{
		$retval = null;
		$template = $this->getParameter('template');
		
		if($template === null) {
			// no template set, we return null so nothing gets rendered
			return null;
		} elseif(YoudsFrameworkToolkit::isPathAbsolute($template)) {
			// the template is an absolute path, ignore the dir
			$directory = dirname($template);
			$template = basename($template);
		} else {
			$directory = $this->getParameter('directory');
		}
		// treat the directory as sprintf format string and inject module name
		$directory = YoudsFrameworkToolkit::expandVariables($directory, array_merge(array_filter($this->getParameters(), 'is_scalar'), array_filter($this->getParameters(), 'is_null')));
		
		$this->setParameter('directory', $directory);
		$this->setParameter('template', $template);
		if(!$this->hasParameter('extension')) {
			$this->setParameter('extension', $this->renderer->getDefaultExtension());
		}
		
		// everything set up for the parent
		return parent::getResourceStreamIdentifier();
	}
}

?>

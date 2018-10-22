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

require_once(__DIR__ . '/YoudsFrameworkTask.php');

/**
 * Configures an YoudsFramework module by reading the module's configuration file.
 *
 * @package    youds
 * @subpackage build
 *
 * @author     Noah Fontes <noah.fontes@bitextender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      1.0.0
 *
 * @version    $Id$
 */
class YoudsFrameworkConfiguremoduleTask extends YoudsFrameworkTask
{
	protected $name;
	protected $prefix = 'module';
	
	/**
	 * Sets the module name.
	 *
	 * @param      string The module name.
	 */
	public function setName($name)
	{
		$this->name = $name;
	}
	
	/**
	 * Sets the property prefix.
	 *
	 * @param      string The prefix.
	 */
	public function setPrefix($prefix)
	{
		$this->prefix = $prefix;
	}
	
	/**
	 * Executes the task.
	 */
	public function main()
	{
		if($this->name === null) {
			throw new BuildException('The name attribute must be specified');
		}
		
		$this->tryLoadYoudsFramework();
		$this->tryBootstrapYoudsFramework();
		
		/* Oookay. This is interesting. */
		$moduleName = $this->name;
		require_once(YoudsFrameworkConfigCache::checkConfig(
			sprintf('%s/%s/%s/%s/module.xml',
				(string)$this->project->getProperty('project.directory'),
				(string)$this->project->getProperty('project.directory.app.modules'),
				$this->name,
				(string)$this->project->getProperty('module.config.directory')
			)
		));
		
		/* Set up us the values.
		 *
		 * XXX: With regards to the defaults:
		 *
		 * You might expect to use the <property>.default properties defined in
		 * build.xml. But this is not so; consider that someone might have decided
		 * to upgrade their project properties but still have some legacy modules
		 * lying around. We need to use the actual YoudsFramework defaults to ensure
		 * consistency.
		 *
		 * If you change this, you're fucking asking for it. */
		$values = array();
		$lowerModuleName = strtolower($moduleName);
		
		$values['action.path'] = YoudsFrameworkConfig::get(
			sprintf('modules.%s.youds.action.path', $lowerModuleName),
			'%core.module_dir%/${moduleName}/actions/${actionName}Action.class.php'
		);
		$values['action.path'] = YoudsFrameworkToolkit::expandVariables(
			$values['action.path'],
			array('moduleName' => $moduleName)
		);
		
		$values['cache.path'] = YoudsFrameworkConfig::get(
			sprintf('modules.%s.youds.cache.path', $lowerModuleName),
			'%core.module_dir%/${moduleName}/cache/${actionName}.xml'
		);
		$values['cache.path'] = YoudsFrameworkToolkit::expandVariables(
			$values['cache.path'],
			array('moduleName' => $moduleName)
		);
		
		$values['templates.directory'] = YoudsFrameworkConfig::get(
			sprintf('modules.%s.youds.template.directory', $lowerModuleName),
			'%core.module_dir%/${module}/templates'
		);
		$values['templates.directory'] = YoudsFrameworkToolkit::expandVariables(
			$values['templates.directory'],
			array('module' => $moduleName)
		);
		
		$values['validate.path'] = YoudsFrameworkConfig::get(
			sprintf('modules.%s.youds.validate.path', $lowerModuleName),
			'%core.module_dir%/${moduleName}/validate/${actionName}.xml'
		);
		$values['validate.path'] = YoudsFrameworkToolkit::expandVariables(
			$values['validate.path'],
			array('moduleName' => $moduleName)
		);
		
		$values['view.path'] = YoudsFrameworkConfig::get(
			sprintf('modules.%s.youds.view.path', $lowerModuleName),
			'%core.module_dir%/${moduleName}/views/${viewName}View.class.php'
		);
		$values['view.path'] = YoudsFrameworkToolkit::expandVariables(
			$values['view.path'],
			array('moduleName' => $moduleName)
		);
		
		$values['view.name'] = YoudsFrameworkConfig::get(
			sprintf('modules.%s.youds.view.name', $lowerModuleName),
			'${actionName}${viewName}'
		);
		
		/* Main screen turn on. */
		foreach($values as $name => $value) {
			$this->project->setUserProperty(sprintf('%s.%s', $this->prefix, $name), $value);
		}
	}
}

?>

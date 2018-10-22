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

require_once(__DIR__ . '/YoudsFrameworkTask.php');

/**
 * Displays all actions in an YoudsFramework module.
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
class YoudsFrameworkDisplayactionsTask extends YoudsFrameworkTask
{
	protected $path = null;
	
	/**
	 * Sets the path to the project directory from which this task will read.
	 *
	 * @param      PhingFile Path to the project directory.
	 */
	public function setPath(PhingFile $path)
	{
		$this->path = $path;
	}
	
	/**
	 * Executes this task.
	 */
	public function main()
	{
		if($this->path === null) {
			throw new BuildException('The path attribute must be specified');
		}
		
		$check = new YoudsFrameworkModuleFilesystemCheck();
		$check->setConfigDirectory($this->project->getProperty('module.config.directory'));
		
		$check->setPath($this->path->getAbsolutePath());
		if(!$check->check()) {
			throw new BuildException('The path attribute must be a valid module base directory');
		}
		
		/* We don't know whether the module is configured or not here, so load the
		 * values we want properly. */
		$this->tryLoadYoudsFramework();
		$this->tryBootstrapYoudsFramework();
		
		require_once(YoudsFrameworkConfigCache::checkConfig(
			sprintf('%s/%s/module.xml',
				$this->path->getAbsolutePath(),
				(string)$this->project->getProperty('module.config.directory')
			)
		));
		
		$actionPath = YoudsFrameworkToolkit::expandVariables(
			YoudsFrameworkToolkit::expandDirectives(YoudsFrameworkConfig::get(
				sprintf('modules.%s.youds.action.path', strtolower($this->path->getName())),
				'%core.module_dir%/${moduleName}/actions/${actionName}Action.class.php'
			)),
			array(
				'moduleName' => $this->path->getName()
			)
		);
		$pattern = '#^' . YoudsFrameworkToolkit::expandVariables(
			/* Blaaaaaaaaauuuuuughhhhhhh... */
			str_replace('\\$\\{actionName\\}', '${actionName}', preg_quote($actionPath, '#')),
			array('actionName' => '(?P<action_name>.*?)')
		) . '$#';
		
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->path->getAbsolutePath()));
		for(; $iterator->valid(); $iterator->next()) {
			$rdi = $iterator->getInnerIterator();
			if($rdi->isDot() || !$rdi->isFile()) {
				continue;
			}
			
			$file = $rdi->getPathname();
			if(preg_match($pattern, $file, $matches)) {
				$this->log(str_replace(DIRECTORY_SEPARATOR, '.', $matches['action_name']));
			}
		}
	}
}

?>

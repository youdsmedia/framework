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
 * Retrieves the base name for a given path.
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
class YoudsFrameworkImportTask extends YoudsFrameworkTask
{
	protected $file;
	
	/**
	 * Sets the build file to be imported.
	 *
	 * @param      PhingFile The file to import.
	 */
	public function setFile(PhingFile $file)
	{
		$this->file = $file;
	}
	
	/**
	 * Executes this task.
	 */
	public function main()
	{
		if($this->file === null) {
			throw new BuildException('The file attribute must be specified');
		}
		
		$return = getcwd();
		try {
			/* Resolve paths correctly: Everything we do as far as
			 * configuration is concerned should be relative to the
			 * new project file. */
			chdir($this->file->getAbsoluteFile()->getParent());
			
			$project = new YoudsFrameworkProxyProject($this->project);
			$project->addReference('phing.parsing.context', new YoudsFrameworkProxyXmlContext($project));
			$project->setUserProperty('phing.file', $this->file->getAbsolutePath());
			
			$project->init();
			
			Phing::setCurrentProject($project);
			ProjectConfigurator::configureProject($project, $this->file);
			
			foreach($project->getTargets() as $name => $target) {
				/* Make sure we don't add proxy targets back to our own project. */
				if($target instanceof YoudsFrameworkProxyTarget && $target->getTarget()->getProject() === $this->project) {
					continue;
				}
				if(array_key_exists($name, $this->project->getTargets())) {
					throw new BuildException(sprintf('Target conflict: %s already exists in project (attempted to add from %s)', $name, $this->file->getAbsolutePath()));
				}
				
				$proxy = new YoudsFrameworkProxyTarget();
				$proxy->setName($name);
				$proxy->setDescription($target->getDescription());
				$proxy->setTarget($target);
				$this->project->addTarget($name, $proxy);
			}

			Phing::setCurrentProject($this->project);
			
			$this->log(sprintf('Importing external build file %s', $this->file->getAbsolutePath()), Project::MSG_INFO);
		}
		catch(Exception $e) {
			$this->log(sprintf('Could not read %s: %s (skipping)', $this->file->getAbsolutePath(), $e->getMessage()), Project::MSG_WARN);
		}
		
		/* Go back from whence we came. */
		chdir($return);
	}
}

?>

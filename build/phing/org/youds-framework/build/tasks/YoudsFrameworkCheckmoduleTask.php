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
 * Validates that a given directory is a module directory for a project.
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
class YoudsFrameworkCheckmoduleTask extends YoudsFrameworkTask
{
	protected $property = null;
	protected $path = null;
	protected $value = true;
	
	/**
	 * Sets the property that this task will modify.
	 *
	 * @param      string The property to modify.
	 */
	public function setProperty($property)
	{
		$this->property = $property;
	}
	
	/**
	 * Sets the path to use to validate the module.
	 *
	 * @param      string The path to use.
	 */
	public function setPath(PhingFile $path)
	{
		$this->path = $path;
	}
	
	/**
	 * Sets the value that the property will contain if the module is valid.
	 *
	 * @param      string The value to which the property will be set.
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}

	/**
	 * Executes this target.
	 */
	public function main()
	{
		if($this->property === null) {
			throw new BuildException('The property attribute must be specified');
		}
		if($this->path === null) {
			throw new BuildException('The path attribute must be specified');
		}
		
		$check = new YoudsFrameworkModuleFilesystemCheck();
		$check->setConfigDirectory($this->project->getProperty('module.config.directory'));
		
		$check->setPath($this->path->getAbsolutePath());
		if($check->check()) {
			$this->project->setUserProperty($this->property, $this->value);
		} else {
			$this->project->setUserProperty($this->property, null);
		}
	}
}

?>

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
 * Sets relevant YoudsFramework properties given an YoudsFramework installation directory.
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
class YoudsFrameworkReadconfigurationTask extends YoudsFrameworkTask
{
	protected $property;
	protected $configurationValue;

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
	 * Sets the configuration value that this task will read.
	 *
	 * @param      string The configuration value to read.
	 */
	public function setConfigurationValue($configurationValue)
	{
		$this->configurationValue = $configurationValue;
	}
	
	/**
	 * Executes the task.
	 */
	public function main()
	{
		if($this->property === null) {
			throw new BuildException('The property attribute must be specified');
		}
		if($this->configurationValue === null) {
			throw new BuildException('The configurationValue attribute must be specified');
		}
		
		$this->tryLoadYoudsFramework();
		/* XXX: We don't need to be bootstrapped for this. That said, we also can't
		 * read configuration data from projects this way. Oh well. */
		
		$this->project->setUserProperty($this->property, YoudsFrameworkConfig::get($this->configurationValue));
	}
}

?>

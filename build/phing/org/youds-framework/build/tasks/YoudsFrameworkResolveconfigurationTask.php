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
 * Resolves YoudsFramework configuration directives and variables.
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
class YoudsFrameworkResolveconfigurationTask extends YoudsFrameworkTask
{
	protected $property;
	protected $string;
	protected $expandDirectives = true;
	protected $variables = array();

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
	 * Sets the string that this task will read.
	 *
	 * @param      string The string to read.
	 */
	public function setString($string)
	{
		$this->string = $string;
	}
	
	/**
	 * Sets whether directives should be expanded as well as variables.
	 *
	 * @param      bool Whether to expand directives in the input string.
	 */
	public function setExpandDirectives($expandDirectives)
	{
		$this->expandDirectives = StringHelper::booleanValue($expandDirectives);
	}
	
	/**
	 * Adds a new variable to this task.
	 *
	 * @return     YoudsFrameworkVariableType The new variable.
	 */
	public function createVariable()
	{
		$variable = new YoudsFrameworkVariableType();
		$this->variables[] = $variable;
		return $variable;
	}
	
	/**
	 * Executes the task.
	 */
	public function main()
	{
		if($this->property === null) {
			throw new BuildException('The property attribute must be specified');
		}
		if($this->string === null) {
			throw new BuildException('The string attribute must be specified');
		}
		
		$this->tryLoadYoudsFramework();
		$this->tryBootstrapYoudsFramework();
		
		$assigns = array();
		foreach($this->variables as $variable) {
			$assigns[$variable->getName()] = $variable->getValue();
		}
		
		$result = YoudsFrameworkToolkit::expandVariables(
			$this->expandDirectives ? YoudsFrameworkToolkit::expandDirectives($this->string) : $this->string,
			$assigns
		);
		
		$this->project->setUserProperty($this->property, $result);
	}
}

?>

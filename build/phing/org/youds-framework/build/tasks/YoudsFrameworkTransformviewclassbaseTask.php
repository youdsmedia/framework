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
 * Transforms a view class base name (like <code>YourAction/Success</code>) to
 * a usable base identifier (like <code>YourAction_Success</code>).
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
class YoudsFrameworkTransformviewclassbaseTask extends YoudsFrameworkTask
{
	protected $property = null;
	protected $string = null;
	
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
	 * Sets the string to transform.
	 *
	 * @param      string The string to transform.
	 */
	public function setString($string)
	{
		$this->string = $string;
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
		
		$result = str_replace('/', '_', YoudsFrameworkToolkit::canonicalName($this->string));
		$this->project->setUserProperty($this->property, $result);
	}
}

?>

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

require_once(__DIR__ . '/YoudsFrameworkType.php');

/**
 * Represents a reference to a path from which additional information is
 * loaded.
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
class YoudsFrameworkFromType extends YoudsFrameworkType
{
	protected $path = null;
	
	/**
	 * Sets the path.
	 *
	 * @param      string The path.
	 */
	public function setPath($path)
	{
		/* This must be created here to prevent the directory from
		 * becoming automatically converted to an absolute path. */
		$this->path = new PhingFile($path);
	}
	
	/**
	 * Gets the path.
	 *
	 * @return     PhingFile The specified path.
	 */
	public function getPath()
	{
		return $this->path;
	}
	
	/**
	 * Returns the referenced object type.
	 *
	 * @return     YoudsFrameworkObjectType The object type.
	 */
	public function getRef(Project $project)
	{
		if(!$this->checked) {
			$stack = array($this);
			$this->dieOnCircularReference($stack, $project);
		}
		
		$object = $this->ref->getReferencedObject($project);
		if(!$object instanceof YoudsFrameworkObjectType) {
			throw new BuildException(sprintf('%s is not an instance of %s', $this->ref->getRefId(), get_class()));
		}
		
		return $object;
	}
}

?>

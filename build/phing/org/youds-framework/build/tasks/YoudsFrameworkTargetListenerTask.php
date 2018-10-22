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

require_once(__DIR__ . '/YoudsFrameworkListenerTask.php');

/**
 * Defines a new listener on targets for this build environment.
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
class YoudsFrameworkTargetListenerTask extends YoudsFrameworkListenerTask
{
	public function main()
	{
		if($this->object === null) {
			throw new BuildException('The object attribute must be specified');
		}
		
		$objectType = $this->object->getReferencedObject($this->project);
		if(!$objectType instanceof YoudsFrameworkObjectType) {
			throw new BuildException('The object attribute must be a reference to an YoudsFramework object type');
		}
		
		$object = $objectType->getInstance();
		if(!$object instanceof YoudsFrameworkIPhingTargetListener) {
			throw new BuildException(sprintf('Cannot add target listener: Object is of type %s which does not implement %s',
				get_class($object), 'YoudsFrameworkIPhingTargetListener'));
		}
		
		$dispatcher = YoudsFrameworkPhingEventDispatcherManager::get($this->project);
		$dispatcher->addTargetListener($object);
	}
}

?>

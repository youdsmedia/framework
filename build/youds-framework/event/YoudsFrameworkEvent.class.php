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
 * Implements a basic YoudsFramework build system event.
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
class YoudsFrameworkEvent implements YoudsFrameworkIEvent
{
	/**
	 * @var        object The source for this event.
	 */
	protected $source;
	
	/**
	 * Retrieves the source object that generated this event.
	 *
	 * @return     object This event's source.
	 *
	 * @author     Noah Fontes <noah.fontes@bitextender.com>
	 * @since      1.0.0
	 */
	public function getSource()
	{
		return $this->source;
	}
	
	/**
	 * Sets the source object for this event.
	 *
	 * @param      object This event's source.
	 *
	 * @author     Noah Fontes <noah.fontes@bitextender.com>
	 * @since      1.0.0
	 */
	public function setSource($source)
	{
		if($this->source !== null) {
			throw new YoudsFrameworkEventBuildException('An event source may not be set multiple times');
		}
		$this->source = $source;
	}
}

?>

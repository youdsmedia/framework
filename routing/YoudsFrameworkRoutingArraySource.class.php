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
 * YoudsFrameworkRoutingArraySource allows you to provide array sources for the routing
 *
 * @package    youds
 * @subpackage routing
 *
 * @author     Dominik del Bondio <ddb@bitxtender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.0
 *
 * @version    $Id$
 */
class YoudsFrameworkRoutingArraySource implements YoudsFrameworkIRoutingSource
{
	/**
	 * @var        array An array with data.
	 */
	protected $data = array();

	/**
	 * Constructor.
	 *
	 * @param      array An array with data.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function __construct(array $data)
	{
		$this->data = $data;
	}

	/**
	 * Retrieves the value for a given entry from the source.
	 *
	 * @param      array An array with the name parts for the entry.
	 * 
	 * @return     mixed The value.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getSource(array $parts)
	{
		return YoudsFrameworkArrayPathDefinition::getValue($parts, $this->data);
	}
}

?>

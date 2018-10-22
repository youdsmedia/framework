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
 * YoudsFrameworkRoutingUserSource allows you to provide an user source for the routing
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
class YoudsFrameworkRoutingUserSource implements YoudsFrameworkIRoutingSource
{
	/**
	 * @var        YoudsFrameworkISecurityUser An user instance.
	 */
	protected $user = null;

	/**
	 * Constructor.
	 *
	 * @param      YoudsFrameworkISecurityUser An user instance.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function __construct(YoudsFrameworkISecurityUser $user)
	{
		$this->user = $user;
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
		if($parts[0] == 'authenticated') {
			return (int) $this->user->isAuthenticated();
		} elseif($parts[0] == 'credentials' && count($parts) > 1) {
			// throw the 'credentials' entry away and check with the parameters left
			array_shift($parts);
			return (int) $this->user->hasCredentials($parts);
		}

		return null;
	}
}

?>

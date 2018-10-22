<?php

// +---------------------------------------------------------------------------+
// | This file is part of the YoudsFramework package.                                   |
// | Copyright (c) 2005-2011 the YoudsFramework Project.                                |
// | Based on the Mojavi3 MVC Framework, Copyright (c) 2003-2005 Sean Kerr.    |
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
 * YoudsFrameworkNullStorage doesn't store what it is given and always returns null on
 * reads. Perfect if you want to use a User, but no sessions.
 *
 * @package    youds
 * @subpackage storage
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.0
 *
 * @version    $Id$
 */
class YoudsFrameworkNullStorage extends YoudsFrameworkStorage
{
	/**
	 * Read data from this storage.
	 *
	 * The preferred format for a key is directory style so naming conflicts can
	 * be avoided.
	 *
	 * @param      string A unique key identifying your data.
	 *
	 * @return     void Always null.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function read($key)
	{
		return null;
	}

	/**
	 * Remove data from this storage.
	 *
	 * The preferred format for a key is directory style so naming conflicts can
	 * be avoided.
	 *
	 * @param      string A unique key identifying your data.
	 *
	 * @return     null Always null.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function remove($key)
	{
		return null;
	}

	/**
	 * Execute the shutdown procedure.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function shutdown()
	{
	}

	/**
	 * Write data to this storage.
	 *
	 * The preferred format for a key is directory style so naming conflicts can
	 * be avoided.
	 *
	 * @param      string A unique key identifying your data.
	 * @param      mixed  Data associated with your key.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function write($key, $data)
	{
	}
}

?>

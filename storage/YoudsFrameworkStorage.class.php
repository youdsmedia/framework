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
 * YoudsFrameworkStorage allows you to customize the way YoudsFramework stores its persistent 
 * data.
 *
 * @package    youds
 * @subpackage storage
 *
 * @author     Sean Kerr <skerr@mojavi.org>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.9.0
 *
 * @version    $Id$
 */
abstract class YoudsFrameworkStorage extends YoudsFrameworkParameterHolder
{
	/**
	 * @var        YoudsFrameworkContext An YoudsFrameworkContext instance.
	 */
	protected $context = null;

	/**
	 * Retrieve the current application context.
	 *
	 * @return     YoudsFrameworkContext An YoudsFrameworkContext instance.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public final function getContext()
	{
		return $this->context;
	}

	/**
	 * Initialize this Storage.
	 *
	 * @param      YoudsFrameworkContext An YoudsFrameworkContext instance.
	 * @param      array        An associative array of initialization parameters.
	 *
	 * @throws     <b>YoudsFrameworkInitializationException</b> If an error occurs while
	 *                                                 initializing this Storage.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function initialize(YoudsFrameworkContext $context, array $parameters = array())
	{
		$this->context = $context;

		$this->setParameters($parameters);
	}

	/**
	 * Executes code necessary to startup the storage (a session, for example).
	 * This code cannot be run in initialize(), because initialization has to
	 * finish completely, for all instances, before a session can be created.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function startup()
	{
	}

	/**
	 * Read data from this storage.
	 *
	 * The preferred format for a key is directory style so naming conflicts can
	 * be avoided.
	 *
	 * @param      string A unique key identifying your data.
	 *
	 * @return     mixed Data associated with the key.
	 *
	 * @throws     <b>YoudsFrameworkStorageException</b> If an error occurs while reading
	 *                                          data from this storage.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	abstract function read($key);

	/**
	 * Remove data from this storage.
	 *
	 * The preferred format for a key is directory style so naming conflicts can
	 * be avoided.
	 *
	 * @param      string A unique key identifying your data.
	 *
	 * @return     mixed Data associated with the key.
	 *
	 * @throws     <b>YoudsFrameworkStorageException</b> If an error occurs while removing
	 *                                          data from this storage.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	abstract function remove($key);

	/**
	 * Execute the shutdown procedure.
	 *
	 * @throws     <b>YoudsFrameworkStorageException</b> If an error occurs while shutting
	 *                                          down this storage.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	abstract function shutdown();

	/**
	 * Write data to this storage.
	 *
	 * The preferred format for a key is directory style so naming conflicts can
	 * be avoided.
	 *
	 * @param      string A unique key identifying your data.
	 * @param      mixed  Data associated with your key.
	 *
	 * @throws     <b>YoudsFrameworkStorageException</b> If an error occurs while writing
	 *                                          to this storage.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	abstract function write($key, $data);
}

?>

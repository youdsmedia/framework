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
 * YoudsFrameworkILegacyConfigHandler is the interface that all old-style config handlers
 * which deal with ConfigValueHolders and parse configs themselves implement.
 *
 * @package    youds
 * @subpackage config
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.0
 *
 * @version    $Id$
 */
interface YoudsFrameworkILegacyConfigHandler
{
	/**
	 * Initialize this ConfigHandler.
	 *
	 * @param      string The path to a validation file for this config handler.
	 * @param      string The parser class to use.
	 * @param      array An associative array of initialization parameters.
	 *
	 * @throws     <b>YoudsFrameworkInitializationException</b> If an error occurs while
	 *                                                 initializing the
	 *                                                 ConfigHandler
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.9.0
	 */
	public function initialize($validationFile = null, $parser = null, $parameters = array());
	
	/**
	 * Execute this configuration handler.
	 *
	 * @param      string An absolute filesystem path to a configuration file.
	 * @param      string Name of the executing context (if any).
	 *
	 * @return     string Data to be written to a cache file.
	 *
	 * @throws     <b>YoudsFrameworkUnreadableException</b> If a requested configuration
	 *                                             file does not exist or is not
	 *                                             readable.
	 * @throws     <b>YoudsFrameworkParseException</b> If a requested configuration file is
	 *                                        improperly formatted.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public function execute($config, $context = null);
}

?>

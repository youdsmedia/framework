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
 * An event listener for YoudsFrameworkDoctrineDatabase.
 *
 * @package    youds
 * @subpackage database
 *
 * @author     David Zülke <david.zuelke@bitextender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      1.0.4
 *
 * @version    $Id$
 */
class YoudsFrameworkDoctrineDatabaseEventListener extends Doctrine_EventListener
{
	/**
	 * @var        YoudsFrameworkDoctrineDatabase The database adapter instance.
	 */
	protected $database;
	
	/**
	 * Constructor, accepts the YoudsFrameworkDoctrineDatabase instance to operate on.
	 *
	 * @param      YoudsFrameworkDoctrineDatabase The corresponding database adapter.
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      1.0.4
	 */
	public function __construct(YoudsFrameworkDoctrineDatabase $database)
	{
		$this->database = $database;
	}
	
	/**
	 * Return the YoudsFrameworkDoctrineDatabase instance associated with this listener.
	 *
	 * @return     YoudsFrameworkDoctrineDatabase
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      1.0.6
	 */
	public function getDatabase()
	{
		return $this->database;
	}
	
	/**
	 * Post-connect listener. Will set charset and run init queries if configured.
	 *
	 * @param      Doctrine_Event The Doctrine event object.
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      1.0.4
	 */
	public function postConnect(Doctrine_Event $event)
	{
		$database = $this->getDatabase();
		
		if($database->hasParameter('charset')) {
			$event->getInvoker()->setCharset($database->getParameter('charset'));
		}
		
		foreach((array)$database->getParameter('init_queries') as $query) {
			$event->getInvoker()->exec($query);
		}
	}
}

?>

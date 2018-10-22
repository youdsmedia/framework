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
 * YoudsFrameworkStreamLoggerAppender appends YoudsFrameworkLoggerMessages to a given stream.
 *
 * @package    youds
 * @subpackage logging
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @author     Bob Zoller <bob@youds.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.2
 *
 * @version    $Id$
 */
class YoudsFrameworkStreamLoggerAppender extends YoudsFrameworkLoggerAppender
{
	/**
	 * @var        The resource of the stream this appender is writing to.
	 */
	protected $handle = null;

	/**
	 * Initialize the object.
	 *
	 * @param      YoudsFrameworkContext An YoudsFrameworkContext instance.
	 * @param      array        An associative array of initialization parameters.
	 *
	 * @author     Bob Zoller <bob@youds.com>
	 * @since      0.10.0
	 */
	public function initialize(YoudsFrameworkContext $context, array $parameters = array())
	{
		parent::initialize($context, $parameters);

		if(!isset($parameters['destination'])) {
			throw new YoudsFrameworkException('No destination given for appending');
		}
	}

	/**
	 * Retrieve the handle for this stream appender.
	 *
	 * @throws     <b>YoudsFrameworkLoggingException</b> if stream cannot be opened for
	 *                                          appending.
	 *
	 * @return     resource The opened resource handle.
	 *
	 * @author     Bob Zoller <bob@youds.com>
	 * @since      0.10.0
	 */
	protected function getHandle()
	{
		$destination = $this->getParameter('destination');
		if(is_null($this->handle)) {
			$this->handle = fopen($destination, $this->getParameter('mode', 'a'));
			if(!$this->handle) {
				throw new YoudsFrameworkLoggingException('Cannot open stream "' . $destination . '".');
			}
		}
		return $this->handle;
	}

	/**
	 * Execute the shutdown procedure.
	 *
	 * If open, close the stream handle.
	 *
	 * @author     Bob Zoller <bob@youds.com>
	 * @since      0.10.0
	 */
	public function shutdown()
	{
		if(!is_null($this->handle)) {
			fclose($this->handle);
		}
	}

	/**
	 * Write log data to this appender.
	 *
	 * @param      YoudsFrameworkLoggerMessage Log data to be written.
	 *
	 * @throws     <b>YoudsFrameworkLoggingException</b> if no Layout is set or the stream
	 *                                          cannot be written.
	 *
	 *
	 * @author     Bob Zoller <bob@youds.com>
	 * @since      0.10.0
	 */
	public function write(YoudsFrameworkLoggerMessage $message)
	{
		if(($layout = $this->getLayout()) === null) {
			throw new YoudsFrameworkLoggingException('No Layout set');
		}

		$str = sprintf("%s\n", $this->getLayout()->format($message));
		if(fwrite($this->getHandle(), $str) === false) {
			throw new YoudsFrameworkLoggingException('Cannot write to stream "' . $this->getParameter('destination') . '".');
		}
	}
}

?>

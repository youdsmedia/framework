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
 * YoudsFrameworkRotatingFileLoggerAppender extends YoudsFrameworkFileLoggerAppender by enabling 
 * per-day log files and removing unwanted old log files.
 *
 * <b>Required parameters:</b>
 *
 * # <b>dir</b>    - [none]              - Log directory
 *
 * <b>Optional parameters:</b>
 *
 * # <b>cycle</b>  - [7]                 - Number of log files to keep.
 * # <b>prefix</b> - [core.app_name-]    - Log filename prefix.
 * # <b>suffix</b> - [.log]              - Log filename suffix.
 *
 * @package    youds
 * @subpackage logging
 *
 * @author     Veikko Mäkinen <mail@veikkomakinen.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.0
 *
 * @version    $Id$
 */
class YoudsFrameworkRotatingFileLoggerAppender extends YoudsFrameworkFileLoggerAppender
{
	/**
	 * Initialize the object.
	 *
	 * @param      YoudsFrameworkContext An YoudsFrameworkContext instance.
	 * @param      array        An associative array of initialization parameters.
	 *
	 * @author     Veikko Mäkinen <mail@veikkomakinen.com>
	 * @since      0.11.0
	 */
	public function initialize(YoudsFrameworkContext $context, array $parameters = array())
	{
		$cycle = 7;
		$prefix = str_replace(' ', '_', YoudsFrameworkConfig::get('core.app_name')) . '-';
		$suffix = '.log';

		if(!isset($parameters['dir'])) {
			throw new YoudsFrameworkLoggingException('No directory defined for rotating logging.');
		}

		$dir = $parameters['dir'];

		if(isset($parameters['cycle'])) {
			$cycle = (int)$parameters['cycle'];
		}
		
		if($cycle < 1) {
			throw new YoudsFrameworkLoggingException('Logging rotation cycle cannot be smaller than 1');
		}

		if(isset($parameters['prefix'])) {
			$prefix = $parameters['prefix'];
		}

		if(isset($parameters['suffix'])) {
			$suffix = $parameters['suffix'];
		}

		$logfile = $dir . $prefix . date('Y-m-d') . $suffix;

		if(!file_exists($logfile)) {

			// todays log file didn't exist so we need to create it
			// and at the same time we'll remove all unwanted history files

			$remove = glob($dir . $prefix . '*-*-*' . $suffix);
			if($remove === false) {
				// who cares, it's just log files
				$remove = array();
			}
			
			foreach(array_slice($remove, 0, -$cycle + 1) as $filename) {
				unlink($filename);
			}
		}

		//it's all up to the parent after this
		$parameters['file'] = $logfile;
		parent::initialize($context, $parameters);
	}
}

?>

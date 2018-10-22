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
 * Version initialization script.
 *
 * @package    youds
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.9.0
 *
 * @version    $Id$
 */

YoudsFrameworkConfig::set('youds.name', 'YoudsFramework');

YoudsFrameworkConfig::set('youds.major_version', '1');
YoudsFrameworkConfig::set('youds.minor_version', '1');
YoudsFrameworkConfig::set('youds.micro_version', '0');
YoudsFrameworkConfig::set('youds.status', 'dev');
YoudsFrameworkConfig::set('youds.branch', 'trunk');

YoudsFrameworkConfig::set('youds.version',
	YoudsFrameworkConfig::get('youds.major_version') . '.' .
	YoudsFrameworkConfig::get('youds.minor_version') . '.' .
	YoudsFrameworkConfig::get('youds.micro_version') .
	(YoudsFrameworkConfig::has('youds.status')
		? '-' . YoudsFrameworkConfig::get('youds.status')
		: '')
);

YoudsFrameworkConfig::set('youds.release',
	YoudsFrameworkConfig::get('youds.name') . '/' .
	YoudsFrameworkConfig::get('youds.version')
);

YoudsFrameworkConfig::set('youds.url', 'http://www.youds.com');

YoudsFrameworkConfig::set('youds_info',
	YoudsFrameworkConfig::get('youds.release') . ' (' .
	YoudsFrameworkConfig::get('youds.url') . ')'
);

?>

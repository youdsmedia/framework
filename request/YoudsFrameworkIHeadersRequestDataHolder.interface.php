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
 * Interface for RequestDataHolders that allow access to Headers.
 *
 * @package    youds
 * @subpackage request
 *
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.0
 *
 * @version    $Id$
 */
interface YoudsFrameworkIHeadersRequestDataHolder
{
	public function hasHeader($name);
	
	public function isHeaderValueEmpty($name);
	
	public function &getHeader($name, $default = null);
	
	public function &getHeaders();
	
	public function getHeaderNames();
	
	public function setHeader($name, $value);
	
	public function setHeaders(array $headers);
	
	public function &removeHeader($name);
	
	public function clearHeaders();
	
	public function mergeHeaders(YoudsFrameworkRequestDataHolder $other);
}

?>

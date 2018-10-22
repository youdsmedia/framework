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
 * Interface for RequestDataHolders that allow access to Parameters.
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
interface YoudsFrameworkIParametersRequestDataHolder
{
	public function hasParameter($name);
	
	public function isParameterValueEmpty($name);
	
	public function &getParameter($name, $default = null);
	
	public function &getParameters();
	
	public function getParameterNames();
	
	public function getFlatParameterNames();
	
	public function setParameter($name, $value);
	
	public function setParameters(array $parameters);
	
	public function &removeParameter($name);
	
	public function clearParameters();
	
	public function mergeParameters(YoudsFrameworkRequestDataHolder $other);
}

?>

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
 * Interface for RequestDataHolders that allow access to Files.
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
interface YoudsFrameworkIFilesRequestDataHolder
{
	public function hasFile($name);
	
	public function isFileValueEmpty($name);
	
	public function &getFile($name, $default = null);
	
	public function &getFiles();
	
	public function getFileNames();
	
	public function getFlatFileNames();
	
	public function setFile($name, YoudsFrameworkUploadedFile $file);
	
	public function setFiles(array $files);
	
	public function &removeFile($name);
	
	public function clearFiles();
	
	public function mergeFiles(YoudsFrameworkRequestDataHolder $other);
}

?>

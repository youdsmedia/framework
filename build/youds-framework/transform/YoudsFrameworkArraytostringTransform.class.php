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
 * Transforms an input array to a delimited string.
 *
 * @package    youds
 * @subpackage build
 *
 * @author     Noah Fontes <noah.fontes@bitextender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      1.0.0
 *
 * @version    $Id$
 */
class YoudsFrameworkArraytostringTransform extends YoudsFrameworkTransform
{
	/**
	 * @var        string The separator between array elements.
	 */
	protected $delimiter = ' ';
	
	/**
	 * Sets the delimiter.
	 *
	 * @param      string The delimiter for the output string.
	 *
	 * @author     Noah Fontes <noah.fontes@bitextender.com>
	 * @since      1.0.0
	 */
	public function setDelimiter($delimiter)
	{
		$this->delimiter = $delimiter;
	}
	
	/**
	 * Transforms an input array to a delimited string.
	 *
	 * @return     string The output string.
	 *
	 * @author     Noah Fontes <noah.fontes@bitextender.com>
	 * @since      1.0.0
	 */
	public function transform()
	{
		$input = $this->getInput();
		
		if($input === null || !is_array($input)) {
			return $input;
		}
		
		$input = str_replace('"', '\\"', $input);
		$input = '"' . implode('"' . $this->delimiter . '"', $input) . '"';
		
		return $input;
	}
}

?>

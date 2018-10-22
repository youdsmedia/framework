<?php

// +---------------------------------------------------------------------------+
// | This file is part of the YoudsFramework package.                                   |
// | Copyright (c) 2005-2010 the YoudsFramework Project.                                |
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
 * YoudsFrameworkBooleanValidator verifies a parameter is a valid boolean
 * 
 * Accepted values are string 0/1, int 0/1, bool true/false, string yes/no,
 * string true/false, string on/off - basically all values that 
 * {@see YoudsFrameworkToolkit::literalize()} will accept.
 * 
 * The value will be casted to the respective boolean unless it's exported. If
 * the export parameter is given, the value will be retained in its original
 * form.
 *
 * @package    youds
 * @subpackage validator
 *
 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      1.0.4
 *
 * @version    $Id$
 */
class YoudsFrameworkBooleanValidator extends YoudsFrameworkValidator
{
	/**
	 * Validates the input.
	 * 
	 * @return     bool The value is a valid boolean
	 * 
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.4
	 */
	protected function validate()
	{
		$value = & $this->getData($this->getArgument());
		$castValue = $value;
		
		if(is_bool($castValue)) {
			// noop
		} elseif(1 === $castValue || '1' === $castValue) {
			$castValue = true;
		} elseif(0 === $castValue || '0' === $castValue) {
			$castValue = false;
		} elseif(is_string($castValue)) {
			$castValue = YoudsFrameworkToolkit::literalize($castValue);
		}
		
		if(is_bool($castValue)) {
			if($this->hasParameter('export')) {
				$this->export($castValue);
			} else {
				$value = $castValue;
			}
			
			return true;
		}
		
		$this->throwError('type');
		
		return false;
	}
}

?>

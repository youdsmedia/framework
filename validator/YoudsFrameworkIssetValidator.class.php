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
 * YoudsFrameworkIssetValidator verifies a parameter is set
 * 
 * The content of the input value is not verified in any manner, it is only
 * checked if the input value exists. (see isset() in PHP)
 *
 * @package    youds
 * @subpackage validator
 *
 * @author     Dominik del Bondio <ddb@bitxtender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.0
 *
 * @version    $Id$
 */
class YoudsFrameworkIssetValidator extends YoudsFrameworkValidator
{
	/**
	 * We need to return true here when this validator is required, because 
	 * otherwise the is*ValueEmpty check would make empty but set fields not 
	 * reach the validate method.
	 *
	 * @see        YoudsFrameworkValidator::checkAllArgumentsSet
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	protected function checkAllArgumentsSet($throwError = true)
	{
		if($this->getParameter('required', true)) {
			return true;
		} else {
			return parent::checkAllArgumentsSet($throwError);
		}
	}

	/**
	 * Validates the input.
	 * 
	 * @return     bool The value is set.
	 * 
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	protected function validate()
	{
		$params = $this->validationParameters->getAll($this->getParameter('source'));

		foreach($this->getArguments() as $argument) {
			if(!$this->curBase->hasValueByChildPath($argument, $params)) {
				$this->throwError();
				return false;
			}
		}

		return true;
	}
}

?>

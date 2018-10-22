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
 * YoudsFrameworkANDOperatorValidator only succeeds if all sub-validators succeeded
 * 
 * Parameters:
 *   'skip_errors' do not submit errors of child validators to validator manager
 *   'break'       break the execution of child validators after first failure
 *
 * @package    youds
 * @subpackage validator
 *
 * @author     Dominik del Bondio <ddb@bitxtender.com>
 * @author     Uwe Mesecke <uwe@mesecke.net>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.0
 *
 * @version    $Id$
 */
class YoudsFrameworkAndoperatorValidator extends YoudsFrameworkOperatorValidator
{
	/**
	 * Validates the operator by executing the child validators.
	 * 
	 * @return     bool True if all child validators resulted successful.
	 * 
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @author     Uwe Mesecke <uwe@mesecke.net>
	 * @since      0.11.0
	 */
	protected function validate()
	{
		$return = true;
		
		foreach($this->children as $child) {
			$result = $child->execute($this->validationParameters);
			$this->result = max($result, $this->result);
			if($result > YoudsFrameworkValidator::SUCCESS) {
				// if one validator fails, the whole operator fails
				$return = false;
				$this->throwError();
				if($this->getParameter('break') || $result == YoudsFrameworkValidator::CRITICAL) {
					break;
				}
			}
		}
		
		return $return;
	}	
}

?>

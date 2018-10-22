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
 * YoudsFrameworkXOROperatorValidator succeeds if only one of two sub-validators 
 * succeeded
 *
 * Parameters:
 *   'skip_errors'  don't submit errors of child validators to validator manager
 *
 * @package    youds
 * @subpackage validator
 *
 * @author     Dominik del Bondio <ddb@bitxtender.com>
 * @author     Uwe Mesecke <uwe@mesecke.net>
 * @author     Ross Lawley <ross.lawley@gmail.com>
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.0
 *
 * @version    $Id$
 */
class YoudsFrameworkXoroperatorValidator extends YoudsFrameworkOperatorValidator
{
	/**
	 * Checks if this operator has a exactly 2 child validators.
	 * 
	 * @throws     <b>YoudsFrameworkValidatorException</b> If the operator doesn't have 
	 *                                            exactly 2 child validators.
	 * 
	 * @author     Uwe Mesecke <uwe@mesecke.net>
	 * @since      0.11.0
	 */
	protected function checkValidSetup()
	{
		if(count($this->children) != 2) {
			throw new YoudsFrameworkValidatorException('XOR allows only exact 2 child validators');
		}
	}

	/**
	 * Validates the operator by returning the by XORing the results of the child
	 * validators.
	 * 
	 * @return     bool True if exactly one child validator succeeded.
	 * 
	 * @author     Uwe Mesecke <uwe@mesecke.net>
	 * @author     Ross Lawley <ross.lawley@gmail.com>
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	protected function validate()
	{
		$children = $this->children;
		
		$child1 = array_shift($children);
		$result1 = $child1->execute($this->validationParameters);
		if($result1 == YoudsFrameworkValidator::CRITICAL) {
			$this->result = $result1;
			$this->throwError();
			return false;
		}
		
		$child2 = array_shift($children);
		$result2 = $child2->execute($this->validationParameters);
		if($result2 == YoudsFrameworkValidator::CRITICAL) {
			$this->result = $result2;
			$this->throwError();
			return false;
		}
		
		$this->result = max($result1, $result2);
		
		if(($result1 == YoudsFrameworkValidator::SUCCESS) xor ($result2 == YoudsFrameworkValidator::SUCCESS)) {
			return true;
		} else {
			$this->throwError();
			return false;
		}
	}	
}

?>

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
 * YoudsFrameworkOROperatorValidator succeeds if at least one sub-validators succeeded
 *
 * Parameters:
 *   'skip_errors' do not submit errors of child validators to validator manager
 *   'break'       break the execution of child validators after first success
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
class YoudsFrameworkOroperatorValidator extends YoudsFrameworkOperatorValidator
{
	/**
	 * Executes the child validators.
	 * 
	 * @return     bool True if at least one child validator succeeded.
	 * 
	 * @author     Uwe Mesecke <uwe@mesecke.net>
	 * @since      0.11.0
	 */
	protected function validate()
	{
		$return = false;
		
		foreach($this->children as $child) {
			$result = $child->execute($this->validationParameters);
			$this->result = max($this->result, $result);

			if($result == YoudsFrameworkValidator::SUCCESS) {
				// if one child validator succeeds, the whole operator succeeds
				$return = true;
				$this->result = $result;
				if($this->getParameter('break')) {
					break;
				}
			} elseif($result == YoudsFrameworkValidator::CRITICAL) {
				break;
			}
		}
		
		if(!$return) {
			$this->throwError();
		}

		return $return;
	}	
}

?>

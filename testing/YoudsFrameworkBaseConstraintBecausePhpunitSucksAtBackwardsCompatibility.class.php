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
 * Base constraint that caters for breaking changes between PHPUnit 3.5 and 3.6.
 * Concrete constraints must implement match().
 *
 * @package    youds
 * @subpackage testing
 *
 * @author     David Zülke <david.zuelke@bitextender.com>
 * @copyright  The YoudsFramework Project
 *
 * @since      1.0.7
 *
 * @version    $Id$
 */
abstract class YoudsFrameworkBaseConstraintBecausePhpunitSucksAtBackwardsCompatibility extends PHPUnit_Framework_Constraint
{
	/**
	 * Overridden function to cover differences between PHPUnit 3.5 and 3.6.
	 * Intentionally made final so people have to use match() from now on.
	 * match() should be abstract really, but isn't, the usual PHPUnit quality...
	 *
	 * @param      mixed  The item to evaluate.
	 * @param      string Additional information about the test (3.6+).
	 * @param      bool   Whether to return a result or throw an exception (3.6+).
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      1.0.7
	 */
	public function evaluate($other, $description = '', $returnResult = false)
	{
		if(version_compare(PHPUnit_Runner_Version::id(), '3.6', '<')) {
			return $this->matches($other);
		} else {
			return parent::evaluate($other, $description, $returnResult);
		}
	}
}

?>

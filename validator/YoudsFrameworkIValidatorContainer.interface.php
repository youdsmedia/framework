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
 * YoudsFrameworkIValidatorContainer is an interface for classes which contains several
 * child validators
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
interface YoudsFrameworkIValidatorContainer
{
	/**
	 * Adds a new validator to the list of children.
	 * 
	 * @param      YoudsFrameworkValidator new child
	 * 
	 * @author     Uwe Mesecke <uwe@mesecke.net>
	 * @since      0.11.0
	 */
	public function addChild(YoudsFrameworkValidator $validator);

	/**
	 * Adds a intermediate result of an validator for the given argument
	 *
	 * @param      YoudsFrameworkValidationArgument The argument
	 * @param      int                     The arguments result.
	 * @param      YoudsFrameworkValidator          The validator (if the error was caused
	 *                                     inside a validator).
	 *
	 * @author     Dominik del Bondio <dominik.del.bondio@bitextender.com>
	 * @since      1.0.0
	 */
	public function addArgumentResult(YoudsFrameworkValidationArgument $argument, $result, $validator = null);

	/**
	 * Adds an incident to the validation result. 
	 *
	 * @param      YoudsFrameworkValidationIncident The incident.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function addIncident(YoudsFrameworkValidationIncident $incident);

	/**
	 * Returns a named child validator.
	 *
	 * @param      YoudsFrameworkValidator The child validator.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getChild($name);

	/**
	 * Returns all child validators.
	 *
	 * @return     array An array of YoudsFrameworkValidator instances.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getChilds();

	/**
	 * Fetches the dependency manager
	 * 
	 * @return     YoudsFrameworkDependencyManager The dependency manager to be used
	 *                                    by child validators.
	 * 
	 * @author     Uwe Mesecke <uwe@mesecke.net>
	 * @since      0.11.0
	 */
	public function getDependencyManager();

}
?>

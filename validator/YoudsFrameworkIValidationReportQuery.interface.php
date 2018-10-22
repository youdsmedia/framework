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
 * YoudsFrameworkIValidationReportQuery allows queries against the validation run report.
 *
 * @package    youds
 * @subpackage validator
 *
 * @author     Dominik del Bondio <dominik.del.bondio@bitextender.com>
 * @author     David Zülke <david.zuelke@bitextender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      1.0.0
 *
 * @version    $Id$
 */
interface YoudsFrameworkIValidationReportQuery
{
	/**
	 * Returns a new YoudsFrameworkIValidationReportQuery which returns only the incidents
	 * for the given argument (and the other existing filter rules).
	 * 
	 * @param      YoudsFrameworkValidationArgument|string|array The argument instance, or
	 *                                                  a parameter name, or an
	 *                                                  array of these elements.
	 * 
	 * @return     YoudsFrameworkIValidationReportQuery
	 * 
	 * @author     Dominik del Bondio <dominik.del.bondio@bitextender.com>
	 * @since      1.0.0
	 */
	public function byArgument($argument);
	
	/**
	 * Returns a new YoudsFrameworkIValidationReportQuery which contains only the incidents
	 * for the given validator (and the other existing filter rules).
	 * 
	 * @param      string|array The name of the validator, or an array of names.
	 * 
	 * @return     YoudsFrameworkIValidationReportQuery
	 * 
	 * @author     Dominik del Bondio <dominik.del.bondio@bitextender.com>
	 * @since      1.0.0
	 */
	public function byValidator($name);
	
	/**
	 * Returns a new YoudsFrameworkIValidationReportQuery which contains only the incidents
	 * for the given error name (and the other existing filter rules).
	 * 
	 * @param      string|array The name of the error, or an array of names.
	 * 
	 * @return     YoudsFrameworkIValidationReportQuery
	 * 
	 * @author     Dominik del Bondio <dominik.del.bondio@bitextender.com>
	 * @since      1.0.0
	 */
	public function byErrorName($name);
	
	/**
	 * Returns a new YoudsFrameworkIValidationReportQuery which contains only the incidents
	 * of the given severity or higher (and the other existing filter rules).
	 * 
	 * @param      int The minimum severity.
	 * 
	 * @return     YoudsFrameworkIValidationReportQuery
	 * 
	 * @author     Dominik del Bondio <dominik.del.bondio@bitextender.com>
	 * @since      1.0.0
	 */
	public function byMinSeverity($minSeverity);
	
	/**
	 * Returns a new YoudsFrameworkIValidationReportQuery which contains only the incidents
	 * of the given severity or lower (and the other existing filter rules).
	 * 
	 * @param      int The maximum severity.
	 * 
	 * @return     YoudsFrameworkIValidationReportQuery
	 * 
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      1.0.0
	 */
	public function byMaxSeverity($maxSeverity);
	
	/**
	 * Retrieves all incidents which match the currently defined filter rules.
	 * 
	 * @return     array An array of YoudsFrameworkValidationIncident objects.
	 * 
	 * @author     Dominik del Bondio <dominik.del.bondio@bitextender.com>
	 * @since      1.0.0
	 */
	public function getIncidents();
	
	/**
	 * Retrieves all YoudsFrameworkValidationError objects which match the currently
	 * defined filter rules.
	 * 
	 * @return     array An array of YoudsFrameworkValidationError objects.
	 * 
	 * @author     Dominik del Bondio <dominik.del.bondio@bitextender.com>
	 * @since      1.0.0
	 */
	public function getErrors();
	
	/**
	 * Retrieves all error messages which match the currently defined filter
	 * rules.
	 * 
	 * @return     array An array of message strings.
	 * 
	 * @author     Dominik del Bondio <dominik.del.bondio@bitextender.com>
	 * @since      1.0.0
	 */
	public function getErrorMessages();
	
	/**
	 * Retrieves all YoudsFrameworkValidationArgument objects which match the currently
	 * defined filter rules.
	 * 
	 * @return     array An array of YoudsFrameworkValidationArgument objects.
	 * 
	 * @author     Dominik del Bondio <dominik.del.bondio@bitextender.com>
	 * @since      1.0.0
	 */
	public function getArguments();
	
	/**
	 * Check if there are any incidents matching the currently defined filter
	 * rules.
	 * 
	 * @return     bool Whether or not any incidents exist for the currently
	 *                  defined filter rules.
	 * 
	 * @author     Dominik del Bondio <dominik.del.bondio@bitextender.com>
	 * @since      1.0.0
	 */
	public function has();
	
	/**
	 * Get the number of incidents matching the currently defined filter rules.
	 * 
	 * @return     int The number of incidents matching the currently defined
	 *                 filter rules.
	 * 
	 * @author     Dominik del Bondio <dominik.del.bondio@bitextender.com>
	 * @since      1.0.0
	 */
	public function count();
	
	/**
	 * Retrieves the highest validation result code of the collection composed of
	 * the currently defined filter rules.
	 *
	 * @return     int An YoudsFrameworkValidator::* severity constant, or null if there is
	 *                 no result for this filter combination. Please remember to
	 *                 do a strict === comparison if you are comparing against
	 *                 YoudsFrameworkValidator::SUCCESS.
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 */
	public function getResult();
}

?>

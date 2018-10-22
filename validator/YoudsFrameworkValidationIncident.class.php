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
 * YoudsFrameworkValidationIncident is erroneous result of an validation run.
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
class YoudsFrameworkValidationIncident
{
	/**
	 * @var        array The errors of this incident.
	 */
	protected $errors = array();

	/**
	 * @var        YoudsFrameworkValidator The source of this incident.
	 */
	protected $validator = null;

	/**
	 * @var        int The severity of this incident.
	 */
	protected $severity = null;

	/**
	 * Constructor
	 *
	 * @param      YoudsFrameworkValidator The validator which caused this incident (null 
	 *                            for errors thrown not in the validation)
	 * @param      int The severity of the incident
	 * @param      array The fields affected by this error.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function __construct($validator, $severity = YoudsFrameworkValidator::ERROR)
	{
		$this->validator = $validator;
		$this->severity = $severity;
	}

	/**
	 * Sets the severity of this incident.
	 *
	 * @param      int The severity.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function setSeverity($severity)
	{
		return $this->severity = $severity;
	}

	/**
	 * Retrieves the severity of this incident.
	 *
	 * @return     int The severity.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getSeverity()
	{
		return $this->severity;
	}

	/**
	 * Adds an error to this incident. This will set the incident of the error to 
	 * this incident instance.
	 *
	 * @param      YoudsFrameworkValidationError The error.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function addError(YoudsFrameworkValidationError $error)
	{
		$error->setIncident($this);
		$this->errors[] = $error;
	}

	/**
	 * Sets the errors of this incident.
	 *
	 * @param      array An array of YoudsFrameworkValidationErrors.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function setErrors(array $errors)
	{
		foreach($errors as $error) {
			$error->setIncident($this);
		}
		$this->errors = $errors;
	}

	/**
	 * Retrieves the errors of this incident.
	 *
	 * @return     array The errors.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Sets the validator of this incident.
	 *
	 * @param      YoudsFrameworkValidator The validator.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function setValidator($validator)
	{
		return $this->validator = $validator;
	}

	/**
	 * Retrieves the validator of this incident.
	 *
	 * @return     YoudsFrameworkValidator The validator.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getValidator()
	{
		return $this->validator;
	}

	/**
	 * Retrieves a list of all erroneous arguments of this incident.
	 *
	 * @return     array An array of YoudsFrameworkValidationArgument.
	 *
	 * @author     Dominik del Bondio <dominik.del.bondio@bitextender.com>
	 * @since      1.0.0
	 */
	public function getArguments()
	{
		$arguments = array();
		foreach($this->errors as $error) {
			foreach($error->getArguments() as $argument) {
				$arguments[$argument->getHash()] = $argument;
			}
		}

		return $arguments;
	}
	
	
	/////////////////////////////////////////////////////////////////////////////
	////////////////////////////// Deprecated Parts /////////////////////////////
	/////////////////////////////////////////////////////////////////////////////
	
	
	/**
	 * Checks if any of the errors of this incident were thrown for the given 
	 * field name.
	 *
	 * @param      string The field name.
	 *
	 * @return     bool The result.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 *
	 * @deprecated 1.0.0
	 */
	public function hasFieldError($fieldname)
	{
		$argument = $this->hasArgumentError(new YoudsFrameworkValidationArgument($fieldname));
		foreach($this->errors as $error) {
			if($error->hasArgument($argument)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Retrieves a list of all fields of all the containing errors.
	 *
	 * @return     array An array of field names.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 *
	 * @deprecated 1.0.0
	 */
	public function getFields()
	{
		$fields = array();
		foreach($this->errors as $error) {
			$fields = array_merge($fields, $error->getFields());
		}

		return array_unique($fields);
	}

	/**
	 * Retrieves the errors which were thrown for the given field.
	 *
	 * @param      string The field name.
	 *
	 * @return     array An array of YoudsFrameworkValidationError.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 *
	 * @deprecated 1.0.0
	 */
	public function getFieldErrors($fieldname)
	{
		$argument = new YoudsFrameworkValidationArgument($fieldname);
		$errors = array();
		foreach($this->errors as $error) {
			if($error->hasArgument($argument)) {
				$errors[] = $error;
			}
		}

		return $errors;
	}

}

?>

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
 * YoudsFrameworkValidationArgument is a tuple of argument name and source that specifies 
 * the argument to validate.
 *
 * @package    youds
 * @subpackage validator
 *
 * @author     Dominik del Bondio <dominik.del.bondio@bitextender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      1.0.0
 *
 * @version    $Id$
 */
class YoudsFrameworkValidationArgument
{
	/**
	 * @var        string the name of the argument.
	 */
	protected $name;
	
	/**
	 * @var        string the name of the source.
	 */
	protected $source;
	
	/**
	 * Create a new YoudsFrameworkValidationArgument instance.
	 * 
	 * @param      string the name of the argument.
	 * @param      string the name of the source, if null, YoudsFrameworkRequestDataHolder::SOURCE_PARAMETERS is used.
	 * 
	 * @author     Dominik del Bondio <dominik.del.bondio@bitextender.com>
	 * @since      1.0.0
	 */
	public function __construct($name, $source = null)
	{
		if($source === null) {
			$source = YoudsFrameworkRequestDataHolder::SOURCE_PARAMETERS;
		}
		$this->name = $name;
		$this->source = $source;
	}
	
	/**
	 * Retrieve the name of the argument for this instance.
	 * 
	 * @return     string the name of the argument
	 *
	 * @author     Dominik del Bondio <dominik.del.bondio@bitextender.com>
	 * @since      1.0.0
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * Retrieve the name of the source for this instance.
	 * 
	 * @return     string the name of the source.
	 *
	 * @author     Dominik del Bondio <dominik.del.bondio@bitextender.com>
	 * @since      1.0.0
	 */
	public function getSource()
	{
		return $this->source;
	}
	
	/**
	 * Get a unique hash value for this YoudsFrameworkValidationArgument.
	 * 
	 * @return     string the hash value
	 *
	 * @author     Dominik del Bondio <dominik.del.bondio@bitextender.com>
	 * @since      1.0.0
	 */
	public function getHash()
	{
		return sprintf('%s/%s', $this->source, $this->name);
	}
}

?>

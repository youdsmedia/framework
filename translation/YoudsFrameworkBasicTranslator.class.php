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
 * YoudsFrameworkBasicTranslator defines some base functions for all translators.
 * 
 * @package    youds
 * @subpackage translation
 *
 * @author     Dominik del Bondio <ddb@bitxtender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.0
 *
 * @version    $Id$
 */
abstract class YoudsFrameworkBasicTranslator implements YoudsFrameworkITranslator
{
	/**
	 * @var        YoudsFrameworkContext An YoudsFrameworkContext instance.
	 */
	protected $context = null;

	/**
	 * Retrieve the current application context.
	 *
	 * @return     YoudsFrameworkContext The current YoudsFrameworkContext instance.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public final function getContext()
	{
		return $this->context;
	}

	/**
	 * Initialize this Translator.
	 *
	 * @param      YoudsFrameworkContext The current application context.
	 * @param      array        An associative array of initialization parameters
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function initialize(YoudsFrameworkContext $context, array $parameters = array())
	{
		$this->context = $context;
	}

	/**
	 * This method gets called by the translation manager when the default locale
	 * has been changed.
	 *
	 * @param      YoudsFrameworkLocale The new default locale.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @since      0.11.0
	 */
	public function localeChanged($newLocale)
	{
	}
}

?>

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
 * YoudsFrameworkTidyFilter cleans up (X)HTML or XML using the tidy extension.
 *
 * @package    youds
 * @subpackage filter
 *
 * @author     David Zülke <david.zuelke@bitextender.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      1.0.0
 *
 * @version    $Id$
 */
class YoudsFrameworkTidyFilter extends YoudsFrameworkFilter implements YoudsFrameworkIGlobalFilter
{
	/**
	 * Execute this filter.
	 *
	 * @param      YoudsFrameworkFilterChain        The filter chain.
	 * @param      YoudsFrameworkExecutionContainer The current execution container.
	 *
	 * @throws     <b>YoudsFrameworkFilterException</b> If an error occurs during execution.
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      1.0.0
	 */
	public function execute(YoudsFrameworkFilterChain $filterChain, YoudsFrameworkExecutionContainer $container)
	{
		// nothing to do so far. let's carry on in the chain
		$filterChain->execute($container);
		
		// fetch some prerequisites
		$response = $container->getResponse();
		$ot = $response->getOutputType();
		$cfg = $this->getParameters();
		
		if(!$response->isContentMutable() || !($output = $response->getContent())) {
			// content empty or response not mutable; it's over!
			return;
		}
		
		if(is_array($cfg['methods']) && !in_array($container->getRequestMethod(), $cfg['methods'])) {
			// we're not allowed to run for this request method
			return;
		}
		
		if(is_array($cfg['output_types']) && !in_array($ot->getName(), $cfg['output_types'])) {
			// we're not allowed to run for this output type
			return;
		}
		
		$tidy = new tidy();
		$tidy->parseString($output, $cfg['tidy_options'], $cfg['tidy_encoding']);
		$tidy->cleanRepair();
		
		if($tidy->getStatus()) {
			// warning or error occurred
			$emsg = sprintf(
				'Tidy Filter encountered the following problems while parsing and cleaning the document: ' . "\n\n%s",
				$tidy->errorBuffer
			);
			
			if(YoudsFrameworkConfig::get('core.use_logging') && $cfg['log_errors']) {
				$lmsg = $emsg . "\n\nResponse content:\n\n" . $response->getContent();
				$lm = $this->context->getLoggerManager();
				$mc = $lm->getDefaultMessageClass();
				$m = new $mc($lmsg, $cfg['logging_severity']);
				$lm->log($m, $cfg['logging_logger']);
			}
			
			// all in all, that didn't go so well. let's see if we should just silently abort instead of throwing an exception
			if(!$cfg['ignore_errors']) {
				throw new YoudsFrameworkParseException($emsg);
			}
		}
		
		$response->setContent((string)$tidy);
	}

	/**
	 * Initialize this filter.
	 *
	 * @param      YoudsFrameworkContext The current application context.
	 * @param      array        An associative array of initialization parameters.
	 *
	 * @throws     <b>YoudsFrameworkFilterException</b> If an error occurs during
	 *                                         initialization
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function initialize(YoudsFrameworkContext $context, array $parameters = array())
	{
		// set defaults
		$this->setParameters(array(
			'methods'          => null,
			'output_types'     => null,
			
			'tidy_options'     => array(),
			'tidy_encoding'    => null,
			
			'ignore_errors'    => true,
			'log_errors'       => true,
			'logging_severity' => YoudsFrameworkLogger::WARN,
			'logging_logger'   => null,
		));
		
		// initialize parent
		parent::initialize($context, $parameters);
	}
}

?>

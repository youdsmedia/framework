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
 * Plain text exception template
 *
 * @package    youds
 * @subpackage exception
 *
 * @author     Veikko Mäkinen <mail@veikkomakinen.com>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.11.0
 *
 * @version    $Id$
 */

// we're not supposed to display errors
// let's throw the exception so it shows up in error logs
if(!ini_get('display_errors')) {
	throw $e;
}

if(!headers_sent()) {
	header('Content-Type: text/plain');	
}

$cols = 80;
if(!defined('STDOUT') || (function_exists('posix_isatty') && !posix_isatty(STDOUT))) {
	// if output is redirected, do not wrap lines after just 80 characters
	$cols = false;
} elseif(file_exists('/bin/stty') && is_executable('/bin/stty') && $sttySize = exec('/bin/stty size 2>/dev/null')) {
	// grab the terminal width for line wrapping if possible
	list(, $cols) = explode(' ', $sttySize);
}

?>

#####################
# Application Error #
#####################

<?php if(count($exceptions) > 1): ?>
<?php $msg = sprintf('The %s was caused by %s. A full chain of exceptions is listed below.', get_class($e), ((count($exceptions) == 2) ? 'another exception' : 'other exceptions')); echo $cols ? wordwrap($msg, $cols, "\n") : $msg; ?>


<?php endif; ?>
<?php foreach($exceptions as $ei => $e): ?>

  <?php echo get_class($e); ?> 
==<?php echo str_repeat("=", strlen(get_class($e))); ?>==

<?php
$lines = explode("\n", trim($e->getMessage()));
foreach($lines as $line):
?>
  <?php echo $cols ? wordwrap($line, $cols-2, "\n  ", true) : $line; ?>

<?php endforeach; ?>

  Stack Trace
  -----------
<?php
$i = 0;
$traceLines = YoudsFrameworkException::getFixedTrace($e, isset($exceptions[$ei+1]) ? $exceptions[$ei+1] : null);
$traceCount = count($traceLines);
foreach($traceLines as $trace) {
	$i++;
	echo sprintf("  %" . strlen($traceCount) . "s: ", $i);
	if(isset($trace['file'])) {
		$msg = $trace['file'] . (isset($trace['line']) ? ':' . $trace['line'] : ''); echo $cols ? wordwrap($msg, $cols - 4 - strlen($traceCount), "\n" . str_repeat(' ', 4 + strlen($traceCount)), true) : $msg;
	} else {
		echo "Unknown file";
	}
	echo "\n";
}

endforeach;
?>


  Version Information
=======================

  YoudsFramework:     <?php echo $cols ? wordwrap(YoudsFrameworkConfig::get('youds.version'), $cols-13, "\n             ", true) : YoudsFrameworkConfig::get('youds.version'); ?>

  PHP:       <?php echo $cols ? wordwrap(phpversion(), $cols-13, "\n             ", true) : phpversion(); ?>

  System:    <?php echo $cols ? wordwrap(php_uname(), $cols-13, "\n             ", true): php_uname(); ?>

  Timestamp: <?php echo $cols ? wordwrap(gmdate(DATE_ISO8601), $cols-13, "\n             ", true) : gmdate(DATE_ISO8601); ?>



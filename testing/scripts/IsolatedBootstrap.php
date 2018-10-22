<?php

$youdsTestSettings = $GLOBALS['AGAVI_TESTING_ISOLATED_TEST_SETTINGS'];
unset($GLOBALS['AGAVI_TESTING_ISOLATED_TEST_SETTINGS']);

if($youdsTestSettings['bootstrap'] || $youdsTestSettings['clearCache']) {
	require(__DIR__ . '/../../testing.php');
}

if($youdsTestSettings['bootstrap']) {
	// when youds is not bootstrapped we don't want / need to load the youds config
	// values from outside the isolation
	YoudsFrameworkConfig::fromArray($GLOBALS['AGAVI_TESTING_CONFIG']);
}
unset($GLOBALS['AGAVI_TESTING_CONFIG']);

if($youdsTestSettings['clearCache']) {
	YoudsFrameworkToolkit::clearCache();
}

$env = null;

if($youdsTestSettings['environment']) {
	$env = $youdsTestSettings['environment'];
}

if($youdsTestSettings['bootstrap']) {
	YoudsFrameworkTesting::bootstrap($env);
}

if($youdsTestSettings['defaultContext']) {
	YoudsFrameworkConfig::set('core.default_context', $youdsTestSettings['defaultContext']);
}

if(!defined('AGAVI_TESTING_BOOTSTRAPPED')) {
	// when PHPUnit runs with preserve global state enabled, AGAVI_TESTING_BOOTSTRAPPED will already be defined
	define('AGAVI_TESTING_BOOTSTRAPPED', true);
}

if(AGAVI_TESTING_ORIGINAL_PHPUNIT_BOOTSTRAP) {
	require_once(AGAVI_TESTING_ORIGINAL_PHPUNIT_BOOTSTRAP);
}


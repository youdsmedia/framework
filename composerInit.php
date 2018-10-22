<?php

class YoudsFrameworkComposerLoaderShim {
	protected $triggerClasses = array(
		'YoudsFrameworkConfig' => true,
		'YoudsFramework' => true,
		'YoudsFrameworkAutoloader' => true,
		'YoudsFrameworkInflector' => true,
		'YoudsFrameworkArrayPathDefinition' => true,
		'YoudsFrameworkVirtualArrayPath' => true,
		'YoudsFrameworkParameterHolder' => true,
		'YoudsFrameworkConfigCache' => true,
		'YoudsFrameworkException' => true,
		'YoudsFrameworkAutoloadException' => true,
		'YoudsFrameworkCacheException' => true,
		'YoudsFrameworkConfigurationException' => true,
		'YoudsFrameworkUnreadableException' => true,
		'YoudsFrameworkParseException' => true,
		'YoudsFrameworkToolkit' => true,
	);
	
	public function trigger($className) {
		if(!empty($this->triggerClasses[$className])) {
			require_once(__DIR__ . '/youds.php');
		}
	}
}

spl_autoload_register(array(new YoudsFrameworkComposerLoaderShim(), 'trigger'));

?>

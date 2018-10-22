<?php

// +---------------------------------------------------------------------------+
// | This file is part of the YoudsFramework package.                                   |
// | Copyright (c) 2005-2011 the YoudsFramework Project.                                |
// | Based on the Mojavi3 MVC Framework, Copyright (c) 2003-2005 Sean Kerr.    |
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
 * YoudsFrameworkConfigCache allows you to customize the format of a configuration
 * file to make it easy-to-use, yet still provide a PHP formatted result
 * for direct inclusion into your modules.
 *
 * @package    youds
 * @subpackage config
 *
 * @author     Sean Kerr <skerr@mojavi.org>
 * @copyright  Authors
 * @copyright  The YoudsFramework Project
 *
 * @since      0.9.0
 *
 * @version    $Id$
 */
class YoudsFrameworkConfigCache
{
	const CACHE_SUBDIR = 'config';

	/**
	 * @var        array An array of config handler instructions.
	 */
	protected static $handlers = null;

	/**
	 * @var        array A string=>bool array containing config handler files and
	 *                   their loaded status.
	 */
	protected static $handlerFiles = array();

	/**
	 * @var        bool Whether there is an entry in self::$handlerFiles that
	 *                  needs processing.
	 */
	protected static $handlersDirty = true;
	
	/**
	 * @var        bool Whether the config handler files have been required.
	 */
	protected static $filesIncluded = false;

	/**
	 * Load a configuration handler.
	 *
	 * @param      string The path of the originally requested configuration file.
	 * @param      string An absolute filesystem path to a configuration file.
	 * @param      string An absolute filesystem path to the cache file that
	 *                    will be written.
	 * @param      string The context which we're currently running.
	 * @param      array  Optional config handler info array.
	 *
	 * @throws     <b>YoudsFrameworkConfigurationException</b> If a requested configuration
	 *                                                file does not have an
	 *                                                associated config handler.
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @author     Dominik del Bondio <dominik.del.bondio@bitextender.com>
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      0.9.0
	 */
	protected static function callHandler($name, $config, $cache, $context, array $handlerInfo = null)
	{
		self::setupHandlers();
		
		if(null === $handlerInfo) {
			// we need to load the handlers first
			$handlerInfo = self::getHandlerInfo($name);
		}

		if($handlerInfo === null) {
			// we do not have a registered handler for this file
			$error = 'Configuration file "%s" does not have a registered handler';
			$error = sprintf($error, $name);
			throw new YoudsFrameworkConfigurationException($error);
		}
		
		$data = self::executeHandler($config, $context, $handlerInfo);
		self::writeCacheFile($config, $cache, $data, false);
	}

	/**
	 * Set up all config handler definitions.
	 * 
	 * Checks whether the handlers have been loaded or the dirtyHandlers flat is
	 * set, and loads any handler that has not been loaded.
	 * 
	 * @author       Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since        1.0.0
	 */
	protected static function setupHandlers()
	{
		self::loadConfigHandlers();
		
		if(self::$handlersDirty) {
			// set handlersdirty to false, prevent an infinite loop
			self::$handlersDirty = false;
			// load additional config handlers
			foreach(self::$handlerFiles as $filename => &$loaded) {
				if(!$loaded) {
					self::loadConfigHandlersFile($filename);
					$loaded = true;
				}
			}
		}
	}
	
	/**
	 * Fetch the handler information for the given filename.
	 * 
	 * @param        string The name of the config file (partial path).
	 * 
	 * @return       array  The handler info.
	 * 
	 * @author       Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since        1.0.0
	 */
	protected static function getHandlerInfo($name)
	{
		// grab the base name of the originally requested config path
		$basename = basename($name);

		$handlerInfo = null;

		if(isset(self::$handlers[$name])) {
			// we have a handler associated with the full configuration path
			$handlerInfo = self::$handlers[$name];
		} elseif(isset(self::$handlers[$basename])) {
			// we have a handler associated with the configuration base name
			$handlerInfo = self::$handlers[$basename];
		} else {
			// let's see if we have any wildcard handlers registered that match
			// this basename
			foreach(self::$handlers as $key => $value)	{
				// replace wildcard chars in the configuration and create the pattern
				$pattern = sprintf('#%s#', str_replace('\*', '.*?', preg_quote($key, '#')));

				if(preg_match($pattern, $name)) {
					$handlerInfo = $value;
					break;
				}
			}
		}
		
		return $handlerInfo;
	}
	
	/**
	 * Execute the config handler for the given file.
	 * 
	 * @param        string The path to the config file (full path).
	 * @param        string The context which we're currently running.
	 * @param        array  The config handler info.
	 * 
	 * @return       string The compiled data.
	 * 
	 * @author       Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since        1.0.0
	 */
	protected static function executeHandler($config, $context, array $handlerInfo)
	{
		// call the handler and retrieve the cache data
		$handler = new $handlerInfo['class'];
		if($handler instanceof YoudsFrameworkIXmlConfigHandler) {
			// a new-style config handler
			// it does not parse the config itself; instead, it is given a complete and merged DOM document
			$doc = YoudsFrameworkXmlConfigParser::run($config, YoudsFrameworkConfig::get('core.environment'), $context, $handlerInfo['transformations'], $handlerInfo['validations']);

			if($context !== null) {
				$context = YoudsFrameworkContext::getInstance($context);
			}

			$handler->initialize($context, $handlerInfo['parameters']);

			try {
				$data = $handler->execute($doc);
			} catch(YoudsFrameworkException $e) {
				throw new $e(sprintf("Compilation of configuration file '%s' failed for the following reason(s):\n\n%s", $config, $e->getMessage()), 0, $e);
			}
		} else {
			$validationFile = null;
			if(isset($handlerInfo['validations'][YoudsFrameworkXmlConfigParser::STAGE_SINGLE][YoudsFrameworkXmlConfigParser::STEP_TRANSFORMATIONS_AFTER][YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_XMLSCHEMA][0])) {
				$validationFile = $handlerInfo['validations'][YoudsFrameworkXmlConfigParser::STAGE_SINGLE][YoudsFrameworkXmlConfigParser::STEP_TRANSFORMATIONS_AFTER][YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_XMLSCHEMA][0];
			}
			$handler->initialize($validationFile, null, $handlerInfo['parameters']);
			$data = $handler->execute($config, $context);
		}
		
		return $data;
	}
	
	/**
	 * Check to see if a configuration file has been modified and if so
	 * recompile the cache file associated with it.
	 *
	 * If the configuration file path is relative, the path itself is relative
	 * to the YoudsFramework "core.app_dir" application setting.
	 *
	 * @param      string A filesystem path to a configuration file.
	 * @param      string An optional context name for which the config should be
	 *                    read.
	 *
	 * @return     string An absolute filesystem path to the cache filename
	 *                    associated with this specified configuration file.
	 *
	 * @throws     <b>YoudsFrameworkUnreadableException</b> If a requested configuration
	 *                                             file does not exist.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public static function checkConfig($config, $context = null)
	{
		$config = YoudsFrameworkToolkit::normalizePath($config);
		// the full filename path to the config, which might not be what we were given.
		$filename = YoudsFrameworkToolkit::isPathAbsolute($config) ? $config : YoudsFrameworkToolkit::normalizePath(YoudsFrameworkConfig::get('core.app_dir')) . '/' . $config;

		if(!is_readable($filename)) {
			throw new YoudsFrameworkUnreadableException('Configuration file "' . $filename . '" does not exist or is unreadable.');
		}

		// the cache filename we'll be using
		$cache = self::getCacheName($config, $context);

		if(self::isModified($filename, $cache)) {
			// configuration file has changed so we need to reparse it
			self::callHandler($config, $filename, $cache, $context);
		}

		return $cache;
	}

	/**
	 * Check if the cached version of a file is up to date.
	 *
	 * @param      string The source file.
	 * @param      string The name of the cached version.
	 *
	 * @return     bool Whether or not the cached file must be updated.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public static function isModified($filename, $cachename)
	{
		return (!is_readable($cachename) || filemtime($filename) > filemtime($cachename));
	}

	/**
	 * Clear all configuration cache files.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public static function clear()
	{
		YoudsFrameworkToolkit::clearCache(self::CACHE_SUBDIR);
	}

	/**
	 * Convert a normal filename into a cache filename.
	 *
	 * @param      string A normal filename.
	 * @param      string A context name.
	 *
	 * @return     string An absolute filesystem path to a cache filename.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public static function getCacheName($config, $context = null)
	{
		$environment = YoudsFrameworkConfig::get('core.environment');

		if(strlen($config) > 3 && ctype_alpha($config[0]) && $config[1] == ':' && ($config[2] == '\\' || $config[2] == '/')) {
			// file is a windows absolute path, strip off the drive letter
			$config = substr($config, 3);
		}

		// replace unfriendly filename characters with an underscore and postfix the name with a php extension
		// see http://trac.youds.com/wiki/RFCs/Ticket932 for an explanation how cache names are constructed
		$cacheName = sprintf(
			'%1$s_%2$s.php',
			preg_replace(
				'/[^\w-_.]/i', 
				'_', 
				sprintf(
					'%1$s_%2$s_%3$s', 
					basename($config), 
					$environment, 
					$context
				)
			),
			sha1(
				sprintf(
					'%1$s_%2$s_%3$s',
					$config,
					$environment,
					$context
				)
			)
		);
		
		return YoudsFrameworkConfig::get('core.cache_dir') . DIRECTORY_SEPARATOR . self::CACHE_SUBDIR . DIRECTORY_SEPARATOR . $cacheName;
	}

	/**
	 * Import a configuration file.
	 *
	 * If the configuration file path is relative, the path itself is relative
	 * to the YoudsFramework "core.app_dir" application setting.
	 *
	 * @param      string A filesystem path to a configuration file.
	 * @param      string A context name.
	 * @param      bool   Only allow this configuration file to be included once
	 *                    per request?
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public static function load($config, $context = null, $once = true)
	{
		$cache = self::checkConfig($config, $context);

		if($once) {
			include_once($cache);
		} else {
			include($cache);
		}
	}

	/**
	 * Load all configuration application and module level handlers.
	 *
	 * @throws     <b>YoudsFrameworkConfigurationException</b> If a configuration related
	 *                                                error occurs.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	protected static function loadConfigHandlers()
	{
		if(self::$handlers !== null) {
			return;
		} else {
			self::$handlers = array();
		}
		
		// some checks first
		if(!defined('LIBXML_DOTTED_VERSION') || (!YoudsFrameworkConfig::get('core.ignore_broken_libxml', false) && !version_compare(LIBXML_DOTTED_VERSION, '2.6.16', 'gt'))) {
			throw new YoudsFrameworkException("A libxml version greater than 2.6.16 is highly recommended. With version 2.6.16 and possibly later releases, validation of XML configuration files will not work and Form Population Filter will eventually fail randomly on some documents due to *severe bugs* in older libxml releases (2.6.16 was released in November 2004, so it is really getting time to update).\n\nIf you still would like to try your luck, disable this message by doing\nYoudsFrameworkConfig::set('core.ignore_broken_libxml', true);\nand\nYoudsFrameworkConfig::set('core.skip_config_validation', true);\nbefore calling\nYoudsFramework::bootstrap();\nin index.php (app/config.php is not the right place for this).\n\nBut be advised that you *will* run into segfaults and other sad situations eventually, so what you should really do is upgrade your libxml install.");
		}
		
		$youdsDir = YoudsFrameworkConfig::get('core.youds_dir');
		
		// :NOTE: fgilcher, 2008-12-03
		// we need this method reentry safe for unit testing
		// sorry for the testing code in the class, but I don't have
		// any other idea to solve the issue
		if(!self::$filesIncluded) {
			// since we only need the parser and handlers when the config is not cached
			// it is sufficient to include them at this stage
			require_once($youdsDir . '/config/YoudsFrameworkILegacyConfigHandler.interface.php');
			require_once($youdsDir . '/config/YoudsFrameworkIXmlConfigHandler.interface.php');
			require_once($youdsDir . '/config/YoudsFrameworkBaseConfigHandler.class.php');
			require_once($youdsDir . '/config/YoudsFrameworkConfigHandler.class.php');
			require_once($youdsDir . '/config/YoudsFrameworkXmlConfigHandler.class.php');
			require_once($youdsDir . '/config/YoudsFrameworkAutoloadConfigHandler.class.php');
			require_once($youdsDir . '/config/YoudsFrameworkConfigHandlersConfigHandler.class.php');
			require_once($youdsDir . '/config/YoudsFrameworkConfigValueHolder.class.php');
			require_once($youdsDir . '/config/YoudsFrameworkConfigParser.class.php');
			require_once($youdsDir . '/config/YoudsFrameworkXmlConfigParser.class.php');
			// extended DOM* classes
			require_once($youdsDir . '/config/util/dom/YoudsFrameworkXmlConfigDomAttr.class.php');
			require_once($youdsDir . '/config/util/dom/YoudsFrameworkXmlConfigDomCharacterData.class.php');
			require_once($youdsDir . '/config/util/dom/YoudsFrameworkXmlConfigDomComment.class.php');
			require_once($youdsDir . '/config/util/dom/YoudsFrameworkXmlConfigDomDocument.class.php');
			require_once($youdsDir . '/config/util/dom/YoudsFrameworkXmlConfigDomDocumentFragment.class.php');
			require_once($youdsDir . '/config/util/dom/YoudsFrameworkXmlConfigDomDocumentType.class.php');
			require_once($youdsDir . '/config/util/dom/YoudsFrameworkXmlConfigDomElement.class.php');
			require_once($youdsDir . '/config/util/dom/YoudsFrameworkXmlConfigDomEntity.class.php');
			require_once($youdsDir . '/config/util/dom/YoudsFrameworkXmlConfigDomEntityReference.class.php');
			require_once($youdsDir . '/config/util/dom/YoudsFrameworkXmlConfigDomNode.class.php');
			require_once($youdsDir . '/config/util/dom/YoudsFrameworkXmlConfigDomNotation.class.php');
			require_once($youdsDir . '/config/util/dom/YoudsFrameworkXmlConfigDomProcessingInstruction.class.php');
			require_once($youdsDir . '/config/util/dom/YoudsFrameworkXmlConfigDomText.class.php');
			// schematron processor
			require_once($youdsDir . '/util/YoudsFrameworkSchematronProcessor.class.php');
			// extended XSL* classes
			if(!YoudsFrameworkConfig::get('core.skip_config_transformations', false)) {
				if(!extension_loaded('xsl')) {
					throw new YoudsFrameworkConfigurationException("You do not have the XSL extension for PHP (ext/xsl) installed or enabled. The extension is used by YoudsFramework to perform XSL transformations in the configuration system to guarantee forwards compatibility of applications.\n\nIf you do not want to or can not install ext/xsl, you may disable all transformations by setting\nYoudsFrameworkConfig::set('core.skip_config_transformations', true);\nbefore calling\nYoudsFramework::bootstrap();\nin index.php (app/config.php is not the right place for this because this is a setting that's specific to your environment or machine).\n\nKeep in mind that disabling transformations mean you *have* to use the latest configuration file formats and namespace versions. Also, certain additional configuration file validations implemented via Schematron will not be performed.");
				}
				require($youdsDir . '/util/YoudsFrameworkXsltProcessor.class.php');
			}
			self::$filesIncluded = true;
		}
		
		// manually create our config_handlers.xml handler
		self::$handlers['config_handlers.xml'] = array(
			'class' => 'YoudsFrameworkConfigHandlersConfigHandler',
			'parameters' => array(
			),
			'transformations' => array(
				YoudsFrameworkXmlConfigParser::STAGE_SINGLE => array(
					// 0.11 -> 1.0
					$youdsDir . '/config/xsl/config_handlers.xsl',
					// 1.0 -> 1.0 with YoudsFrameworkReturnArrayConfigHandler <transformation> for YoudsFramework 1.1
					$youdsDir . '/config/xsl/config_handlers.xsl',
				),
				YoudsFrameworkXmlConfigParser::STAGE_COMPILATION => array(
				),
			),
			'validations' => array(
				YoudsFrameworkXmlConfigParser::STAGE_SINGLE => array(
					YoudsFrameworkXmlConfigParser::STEP_TRANSFORMATIONS_BEFORE => array(
					),
					YoudsFrameworkXmlConfigParser::STEP_TRANSFORMATIONS_AFTER => array(
						YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_XMLSCHEMA => array(
							$youdsDir . '/config/xsd/config_handlers.xsd',
						),
						YoudsFrameworkXmlConfigParser::VALIDATION_TYPE_SCHEMATRON => array(
							$youdsDir . '/config/sch/config_handlers.sch',
						),
					),
				),
				YoudsFrameworkXmlConfigParser::STAGE_COMPILATION => array(
					YoudsFrameworkXmlConfigParser::STEP_TRANSFORMATIONS_BEFORE => array(),
					YoudsFrameworkXmlConfigParser::STEP_TRANSFORMATIONS_AFTER => array()
				),
			),
		);

		$cfg = YoudsFrameworkConfig::get('core.config_dir') . '/config_handlers.xml';
		if(!is_readable($cfg)) {
			$cfg = YoudsFrameworkConfig::get('core.system_config_dir') . '/config_handlers.xml';
		}
		// application configuration handlers
		self::loadConfigHandlersFile($cfg);
	}
	
	/**
	 * Load the config handlers from the given config file.
	 * Existing handlers will not be overwritten.
	 * 
	 * @param      string The path to a config_handlers.xml file.
	 * 
	 * @author     Felix Gilcher <felix.gilcher@bitextender.com>
	 * @since      1.0.0
	 */
	protected static function loadConfigHandlersFile($cfg)
	{
		self::$handlers = (array)self::$handlers + include(YoudsFrameworkConfigCache::checkConfig($cfg));
	}

	/**
	 * Schedules a config handlers file to be loaded.
	 * 
	 * @param      string The path to a config_handlers.xml file.
	 * 
	 * @author     Dominik del Bondio <dominik.del.bondio@bitextender.com>
	 * @since      1.0.0
	 */
	public static function addConfigHandlersFile($filename)
	{
		if(!isset(self::$handlerFiles[$filename])) {
			if(!is_readable($filename)) {
				throw new YoudsFrameworkUnreadableException('Configuration file "' . $filename . '" does not exist or is unreadable.');
			}
			
			self::$handlerFiles[$filename] = false;
			self::$handlersDirty = true;
		}
	}

	/**
	 * Write a cache file.
	 *
	 * @param      string An absolute filesystem path to a configuration file.
	 * @param      string An absolute filesystem path to the cache file that
	 *                    will be written.
	 * @param      string Data to be written to the cache file.
	 * @param      bool   Should we append the data?
	 *
	 * @throws     <b>YoudsFrameworkCacheException</b> If the cache file cannot be written.
	 *
	 * @author     Sean Kerr <skerr@mojavi.org>
	 * @since      0.9.0
	 */
	public static function writeCacheFile($config, $cache, $data, $append = false)
	{
		$perms = fileperms(YoudsFrameworkConfig::get('core.cache_dir')) ^ 0x4000;

		$cacheDir = YoudsFrameworkConfig::get('core.cache_dir') . DIRECTORY_SEPARATOR . self::CACHE_SUBDIR;

		YoudsFrameworkToolkit::mkdir($cacheDir, $perms);

		if($append && is_readable($cache)) {
			$data = file_get_contents($cache) . $data;
		}

		$tmpName = tempnam($cacheDir, basename($cache));
		if(@file_put_contents($tmpName, $data) !== false) {
			// that worked, but that doesn't mean we're safe yet
			// first, we cannot know if the destination directory really was writeable, as tempnam() falls back to the system temp dir
			// second, with php < 5.2.6 on win32 renaming to an already existing file doesn't work, but copy does
			// so we simply assume that when rename() fails that we are on win32 and try to use copy() followed by unlink()
			// if that also fails, we know something's odd
			if(@rename($tmpName, $cache) || (@copy($tmpName, $cache) && unlink($tmpName))) {
				// alright, it did work after all. chmod() and bail out.
				chmod($cache, $perms);
				return;
			}
		}
		
		// still here?
		// that means we could not write the cache file
		$error = 'Failed to write cache file "%s" generated from ' . 'configuration file "%s".';
		$error .= "\n\n";
		$error .= 'Please make sure you have set correct write permissions for directory "%s".';
		$error = sprintf($error, $cache, $config, YoudsFrameworkConfig::get('core.cache_dir'));
		throw new YoudsFrameworkCacheException($error);
	}

	/**
	 * Parses a config file with the ConfigParser for the extension of the given
	 * file.
	 *
	 * @param      string An absolute filesystem path to a configuration file.
	 * @param      bool   Whether the config parser class should be autoloaded if
	 *                    the class doesn't exist.
	 * @param      string A path to a validation file for this config file.
	 * @param      string A class name which specifies an parser to be used.
	 *
	 * @return     YoudsFrameworkConfigValueHolder An abstract representation of the
	 *                                    config file.
	 *
	 * @throws     <b>YoudsFrameworkConfigurationException</b> If the parser for the
	 *             extension couldn't be found.
	 *
	 * @author     Dominik del Bondio <ddb@bitxtender.com>
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 *
	 * @deprecated New-style config handlers don't call this method anymore. To be
	 *             removed in YoudsFramework 1.1
	 */
	public static function parseConfig($config, $autoloadParser = true, $validationFile = null, $parserClass = null)
	{
		$parser = new YoudsFrameworkConfigParser();

		return $parser->parse($config, $validationFile);
	}
}

?>

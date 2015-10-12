<?php
namespace common\base;

/**
 * Provides convenience methods for using the spl_autoload feature within PHP.
 */
class Autoload {
	/**
	 * Sets up the spl autoload feature within PHP.
	 *
	 * @param String $phpPath The path of where PHP files are kept.
	 * @param Boolean $enableTests Should include test files for autoloading
	 */
	public static function setup($phpPath, $enableTests) {
		// Set up auto-loading
		spl_autoload_register(function ($className) use ($phpPath, $enableTests) {
			// Replace namespace backslashes with folder directory forward slashes
			$className = str_replace("\\", "/", $className);

			// Normal PHP path
			$path = $phpPath . "/" . $className . ".php";
			if (file_exists($path)) {
				/** @noinspection PhpIncludeInspection */
				include_once($path);
			}

			// PHPTests path
			$path = $phpPath . "tests/" . $className . ".php";
			if ($enableTests && file_exists($path)) {
				/** @noinspection PhpIncludeInspection */
				include_once($path);
			}
		});
	}
}

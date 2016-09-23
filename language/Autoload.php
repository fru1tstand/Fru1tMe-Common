<?php
namespace me\fru1t\common\language;
use Exception;

/**
 * Because PHP is a runtime-parsed language, files containing classes that are used in other files
 * need to be included via "require" or "include" or either of their "*_once" variation. Introduce
 * auto-loading, which removes this requirement. By simply structuring your PHP files and folders
 * Java-style* (ie. A folder for each namespace area with the file name the name of the class), one
 * can tell PHP to automatically include a given class, if the definition is not already loaded.
 *
 * Java-style structuring:
 * 1) Files may only declare up to 1 class.
 * 2) The file name must be the same as the class name (case-sensitive), and end with ".php"
 * 3) Classes must have a namespace.
 * 4) The file must reside in the path equal to the namespace.
 *
 * Take this file as an example. This file declares a class called Autoload, and thus the file is
 * named "Autoload.php". The namespace (or package) is "me\fru1t\language" and thus, the file
 * resides in the "<php source>/me/fru1t/language" folder.
 */
class Autoload {
	/**
	 * Sets up the spl autoload feature within PHP.
	 *
	 * @param string $phpPath The path of where PHP files are kept.
	 */
	public static function setup(string $phpPath) {
		// Set up auto-loading
		spl_autoload_register(function ($className) use ($phpPath) {
			// Replace namespace backslashes with folder directory forward slashes
			$className = str_replace("\\", "/", $className);

			// Normal PHP path
			$path = $phpPath . "/" . $className . ".php";
			if (file_exists($path)) {
				/** @noinspection PhpIncludeInspection */
				include_once($path);
			} else {
				throw new Exception("Could not find PHP path: " . $phpPath);
			}
		});
	}
}

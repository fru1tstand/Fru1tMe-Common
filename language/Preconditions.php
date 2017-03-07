<?php
namespace me\fru1t\common\language;

/**
 * Common checks done for data validation within functions.
 */
class Preconditions {
	/**
	 * Returns true if the passed parameter is not null and has elements.
	 * @param mixed $array
	 * @return bool
	 */
	public static function arrayNullOrEmpty($array): bool {
		return is_null($array) || !is_array($array) || count($array) == 0;
	}

	/**
	 * Check if one or more variables are null. Returns true on the first encountered null variable.
	 * Otherwise, returns true. Passing an array will NOT check the contents of the array. Use
	 * the array manipulations methods in this class instead.
   * @see is_null()
	 * @param mixed $vars,... Any number of objects to check
	 * @return bool
	 */
	public static function isNull(...$vars): bool {
		foreach ($vars as $var) {
			if (is_null($var)) {
				return true;
			}
		}
		return false;
	}

  /**
   * Checks if the given object is any of the given values.
   * @param $obj
   * @param array $values
   * @return bool
   */
  public static function isAnyOf($obj, array $values): bool {
    foreach ($values as $value) {
      if ($obj === $value) {
        return true;
      }
    }
    return false;
  }

	// String manipulations
  /**
   * Checks if a given string is null, empty, or just whitespace.
   * @param null|string $str
   * @return bool
   */
  public static function isNullEmptyOrWhitespace(?string $str): bool {
    return self::isNull($str) || strlen(preg_replace('[^\S]', '', $str)) == 0;
  }

	// Standardized naming/aliasing.
  /**
   * @see is_dir()
   * @param string $folder
   * @return bool
   */
	public static function isFolder(string $folder): bool {
	  return is_dir($folder);
  }

  /**
   * @see is_dir()
   * @param string $folder
   * @return bool
   */
  public static function isDir(string $folder): bool {
    return is_dir($folder);
  }

  /**
   * @see is_file()
   * @param string $fileName
   * @return bool
   */
  public static function isFile(string $fileName): bool {
    return is_file($fileName);
  }
}

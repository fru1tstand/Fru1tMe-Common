<?php
namespace common\base;

/**
 * Common checks done for data validation within functions.
 */
class Preconditions {
	/**
	 * Returns true if the passed parameter is not null and has elements.
	 *
	 * @param mixed $array
	 * @return bool
	 */
	public static function arrayNullOrEmpty($array): bool {
		return is_null($array) || !is_array($array) || count($array) == 0;
	}

	/**
	 * Check if one or more variables are null. Returns true on the first encountered null variable.
	 * Otherwise, returns true.
	 *
	 * @param ...$vars
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
}

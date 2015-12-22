<?php
namespace common\base;

/**
 * Contains methods and constructs that extend the core functionality of PHP.
 * @package common\base
 */
class Lang {
	/**
	 * Check if one or more variables are null. Returns true on the first encountered null variable.
	 * Otherwise, returns true.
	 *
	 * @param mixed[] ...$vars
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

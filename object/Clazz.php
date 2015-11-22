<?php
namespace common\object;

/**
 * Attempts to emulate Java-like class object capabilities with none of the reflection.
 * @package common\object
 */
interface Clazz {
	/**
	 * Returns the fully qualified classname for this class.
	 *
	 * @return string
	 */
	public static function getClass();
}

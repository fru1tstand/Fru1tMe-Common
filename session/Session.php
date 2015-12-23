<?php
namespace common\session;

/**
 * Handles session data
 * @version 0.2
 */
class Session {
	//Single static instance
	private function __construct() {}
	private static $hasSessionStarted = false;

	/**
	 * Starts a named session.
	 *
	 * @param string $sessionName The name of the session
	 */
	public static function setup(string $sessionName) {
		if (self::$hasSessionStarted) {
			return;
		}

		self::$hasSessionStarted = true;
		session_name($sessionName);
		session_start();
	}

	/**
	 * Stores a value within the session array. Returns false if the session hasn't started. Keys
	 * that are already set will be overwritten. Be weary that objects should be serialized if
	 * stored here. Arrays can be stored as-is without serialization (assuming its contents are
	 * of string or array forms).
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return boolean False if the session doesn't exist; otherwise, true
	 */
	public static function set(string $key, $value): bool {
		if (!self::$hasSessionStarted) {
			return false;
		}

		$_SESSION[$key] = $value;
		return true;
	}

	/**
	 * Returns the value stored at the given key or null if none exists or the session hasn't been
	 * started. Returns values as stored (arrays, serialized objects, etc).
	 *
	 * @param string $key The key to get
	 * @return mixed The value at key if both the key and session exist; otherwise, null
	 */
	public static function get($key) {
		if (!self::exists($key)) {
			return false;
		}

		return $_SESSION[$key];
	}


	/**
	 * Deletes the given key. Returns false if the key doesn't exist or the session been started.
	 *
	 * @param string $key The key to delete
	 * @return boolean False if the session doesn't exist; otherwise, true
	 */
	public static function delete($key): bool {
		if (!self::exists($key)) {
			return false;
		}

		unset($_SESSION[$key]);
		return true;
	}

	/**
	 * Returns if the given key exists within the session.
	 *
	 * @param string $key
	 * @return bool
	 */
	public static function exists(string $key): bool {
		if (!self::$hasSessionStarted) {
			return false;
		}

		return isset($_SESSION[$key]);
	}
}
?>

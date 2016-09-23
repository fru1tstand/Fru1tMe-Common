<?php
namespace me\fru1t\common\language;
use RuntimeException;

/**
 * Handles interfacing with PHP sessions.
 */
class Session {
	private function __construct() {}
	private static $hasSessionStarted = false;

	/**
	 * Starts a named session.
	 *
	 * @param string $sessionName The name of the session
	 */
	public static function setup(string $sessionName) {
		if (self::$hasSessionStarted) {
			throw new RuntimeException("Session cannot be setup twice.");
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
    self::checkSetup();

		$_SESSION[$key] = $value;
		return true;
	}

	/**
	 * Returns the value stored at the given key or null if none exists or the session hasn't been
	 * started. Returns values as stored (arrays, serialized objects, etc).
	 *
	 * @param string $key The key to get
	 * @return mixed|null The value at key if both the key and session exist; otherwise, null
	 */
	public static function get($key) {
    self::checkSetup();

		if (!self::exists($key)) {
			return null;
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
	  self::checkSetup();

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
    self::checkSetup();

		return isset($_SESSION[$key]);
	}

	private static function checkSetup() {
    if (!self::$hasSessionStarted) {
      throw new RuntimeException("Session hasn't been set up. See Session::setup.");
    }
  }
}

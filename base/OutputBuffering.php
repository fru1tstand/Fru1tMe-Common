<?php
namespace common\base;

/**
 * A simple wrapper class for controlling a single instance of the
 * PHP output buffering methods.
 *
 * @version 0.1
 */
class OutputBuffering {
	/* @type boolean */
	private static $hasStarted = false;

	/**
	 * Starts the buffering session.
	 */
	public static function start() {
		if (self::$hasStarted) {
			return;
		}
		ob_start();
		self::$hasStarted = true;
	}

	/**
	 * Ends the buffering session and flushes the contents
	 * of the buffer to output.
	 */
	public static function flush() {
		if (!self::$hasStarted) {
			return;
		}
		ob_end_flush();
		self::$hasStarted = false;
	}

	/**
	 * Ends the buffering session and clears the contents
	 * of the buffer.
	 */
	public static function clean() {
		if (!self::$hasStarted) {
			return;
		}
		ob_end_clean();
		self::$hasStarted = false;
	}
}

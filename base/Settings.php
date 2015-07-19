<?php
namespace common\base;

/**
 * Class Settings
 * @package common\base
 *
 * Defines the basic settings for an FM website
 */
abstract class Settings {
	/**
	 * Name of the php web session
	 */
	const SESSION_NAME = "Fru1tMe";

	/**
	 * Determines if debugging is enabled. Mainly used for debug messages, but also has some
	 * other side effects.
	 * @return bool
	 */
	public abstract function enableDebug();
}
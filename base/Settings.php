<?php
namespace common\base;

abstract class BaseSettings {
	public const SESSION_NAME = "Fru1tMe";

	/**
	 * Determines if debugging is enabled. Mainly used for debug messages, but also has some
	 * other side effects.
	 * @return bool
	 */
	public abstract function enableDebug();
}
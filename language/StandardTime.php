<?php
namespace me\fru1t\common\language;

/**
 * Unifies timezones by providing methods of converting back and forth between UTC+0 and whatever
 * timezone you choose to be in. StandardTime only uses Seconds.
 */
class StandardTime {
	// Constants
	const SECONDS_IN_DAY = 86400;

	// Non-instantiable
	private function __construct() { }

	// Conversion methods
	/**
	 * Converts the passed time from whatever UTC timezone it was in, to UTC+0, in Seconds from the
   * unix epoch.
   *
	 * @param int $time
	 * @param int $from
	 * @return int
	 */
	public static function toStandard(int $time, int $from): int {
		return self::convert($time, $from, 0);
	}

	/**
	 * Converts the passed time from UTC+0 to the requested timezone, in Seconds from the unix epoch.
   *
	 * @param int $time
	 * @param int $to
	 * @return int
	 */
	public static function fromStandard(int $time, int $to): int {
		return self::convert($time, 0, $to);
	}

	/**
   * Converts the passed time from a specified timezone to the requested timezone, in second from the
   * unix epoch.
   *
	 * @param int $time
	 * @param int $from UTC timezone that the passed time is in
	 * @param int $to UTC timezone that you want
	 * @return int
	 */
	public static function convert(int $time, int $from, int $to): int {
		// From -8 to +2 should add 10 hours, ((+2 [to]) - (-8 [from])) = 10
		return $time + (60 * 60 * ($to - $from));
	}

	/**
	 * Gets the current UTC+0 time in seconds from the unix epoch.
   *
	 * @return int
	 */
	public static function getTime(): int {
		if (!date_default_timezone_get() == "UTC")
			date_default_timezone_set("UTC");
		return time();
	}

	/**
	 * Returns the date portion of the given time in seconds from the unix epoch.
   *
	 * @param int $time
	 * @return int
	 */
	public static function floorToDate($time): int {
		return $time - ($time % self::SECONDS_IN_DAY);
	}
}

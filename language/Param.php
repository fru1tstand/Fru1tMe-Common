<?php
namespace me\fru1t\common\language;

/**
 * Provides error-safe methods for retrieving parameter content.
 */
class Param {
	/** Specifies to check for the POST parameter when searching in requests. */
	const POST = 0x01;

	/** Specifies to check for the GET parameter when searching in requests. */
	const GET = 0x10;

	/**
	 * Returns if the given parameter is equal to the given value.
	 *
	 * @param string $paramName
	 * @param string $expectedValue
	 * @param int $paramMethod (optional) Defaults to checking both Post and Get.
	 * @return bool
	 */
	public static function isParamEqualTo(
			string $paramName,
			string $expectedValue,
			int $paramMethod = self::GET | self::POST): bool {
		if ($paramName === "") {
			return false;
		}
		if (($paramMethod & self::GET) > 0
				&& self::fetchGet($paramName) === $expectedValue) {
			return true;
		}
		if (($paramMethod & self::POST) > 0
				&& self::fetchPost($paramName) === $expectedValue) {
			return true;
		}

		return false;
	}

	/**
	 * Returns the value of the specified GET parameter or null if it doesn't exist.
	 *
	 * @param string $getParameterName
	 * @return string | null
	 */
	public static function fetchGet(string $getParameterName): ?string {
		return self::fetch($getParameterName, $_GET);
	}

	/**
	 * Returns the value of the specified POST parameter or null if it doesn't exist.
	 *
	 * @param string $paramName
	 * @return string | null
	 */
	public static function fetchPost(string $paramName): ?string {
		return self::fetch($paramName, $_POST);
	}

	/**
	 * @param string $paramName
	 * @param array $paramArray
	 * @return string | null
	 */
	private static function fetch(string $paramName, array $paramArray): ?string {
		if (isset($paramArray[$paramName])) {
			return $paramArray[$paramName];
		}
		return null;
	}
}

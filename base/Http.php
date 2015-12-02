<?php
namespace common\base;

/**
 * Contains methods pertaining to HTTP requests and data.
 */
class Http {
	/** Specifies to check for the POST parameter when searching in requests. */
	const PARAM_METHOD_POST = 0x01;
	/** Specifies to check for the GET parameter when searching in requests. */
	const PARAM_METHOD_GET = 0x10;


	/**
	 * Returns if the given parameter is equal to the given value.
	 *
	 * @param string $paramName
	 * @param string $expectedValue
	 * @param int $paramMethod
	 * @return bool
	 */
	public static function isParamEqualTo(
			string $paramName,
			string $expectedValue,
			int $paramMethod = self::PARAM_METHOD_GET | self::PARAM_METHOD_POST): bool {
		if (($paramMethod & self::PARAM_METHOD_GET) > 0
				&& self::getGetParamValue($paramName) == $expectedValue) {
			return true;
		}
		if (($paramMethod & self::PARAM_METHOD_POST) > 0
				&& self::getPostParamValue($paramName) == $expectedValue) {
			return true;
		}

		return false;
	}

	/**
	 * Returns the value of the specified GET parameter or null if it doesn't exist.
	 *
	 * @param string $paramName
	 * @return string | null
	 */
	public static function getGetParamValue(string $paramName): string {
		return self::getParamValue($paramName, $_GET);
	}

	/**
	 * Returns the value of the specified POST parameter or null if it doesn't exist.
	 *
	 * @param string $paramName
	 * @return string
	 */
	public static function getPostParamValue(string $paramName): string {
		return self::getParamValue($paramName, $_POST);
	}


	/**
	 * @param string $paramName
	 * @param array $paramArray
	 * @return string
	 */
	private static function getParamValue(string $paramName, array $paramArray): string {
		if (isset($paramArray[$paramName])) {
			return $paramArray[$paramName];
		}
		return null;
	}
}

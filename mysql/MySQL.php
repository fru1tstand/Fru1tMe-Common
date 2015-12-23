<?php
namespace common\mysql;
use common\base\Preconditions;
use mysqli;
use mysqli_sql_exception;

/**
 * Class MySQL
 */
class MySQL {
	/** @type mysqli */
	private static $connection = null;

	private static $host = null;
	private static $username = null;
	private static $password = null;
	private static $schema = null;

	/**
	 * Sets up the current session's mysql connection.
	 *
	 * @param string $host
	 * @param string $username
	 * @param string $password
	 * @param string $schema
	 */
	public static function setup(string $host, string $username, string $password, string $schema) {
		self::$host = $host;
		self::$username = $username;
		self::$password = $password;
		self::$schema = $schema;
	}

	/**
	 * Creates and returns a new query builder with this session's mysql connection.
	 * @return QueryBuilder
	 */
	public static function newQueryBuilder(): QueryBuilder {
		return new QueryBuilder(self::getConnection());
	}

	private static function getConnection(): mysqli {
		if (is_null(self::$connection)) {
			if (Preconditions::isNull(self::$host, self::$username, self::$password,
					self::$schema)) {
				throw new mysqli_sql_exception("MySQL was never set up with database credentials");
			}

			self::$connection =
					new mysqli(self::$host, self::$username, self::$password, self::$schema);
		}
		return self::$connection;
	}
}

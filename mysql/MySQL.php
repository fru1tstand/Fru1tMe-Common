<?php
namespace me\fru1t\common\mysql;
use me\fru1t\common\language\Preconditions;
use mysqli;
use mysqli_sql_exception;

/**
 * Wraps the mysqli object in a singleton instance.
 */
class MySQL {
	const CHARSET_UTF_8 = "utf8";

	/** @var mysqli|null */
	private static $connection = null;

  /** @var string|null */
	private static $host = null;
  /** @var string|null */
	private static $username = null;
  /** @var string|null */
	private static $password = null;
  /** @var string|null */
	private static $schema = null;
  /** @var string|null */
	private static $charset = null;

	/**
	 * Defines the mysqli connection that will be established for this session. Requires host,
   * username, password, and schema (database) to connect to. This class only supports a single
   * instance/connection to the database. If more than 1 connection or databases are required, do
   * not use this class.
	 *
	 * @param string $host The network address to connect to.
	 * @param string $username The username credentials to use.
	 * @param string $password The password for the credentials.
	 * @param string $schema The schema (database) name to connect to.
	 * @param string $charset (optional) Defaults to {@link MySQL::CHARSET_UTF_8}
	 */
	public static function setup(
			string $host,
			string $username,
			string $password,
			string $schema,
			string $charset = MySQL::CHARSET_UTF_8) {
		self::$host = $host;
		self::$username = $username;
		self::$password = $password;
		self::$schema = $schema;
		self::$charset = $charset;
	}

	/**
	 * Creates and returns a new query builder with this session's mysql connection.
	 *
	 * @return QueryBuilder
	 */
	public static function newQueryBuilder(): QueryBuilder {
		return new QueryBuilder(self::getConnection());
	}

	private static function getConnection(): mysqli {
		if (is_null(self::$connection)) {
			if (Preconditions::isNull(self::$host, self::$username, self::$password, self::$schema)) {
				throw new mysqli_sql_exception("MySQL was never set up with database credentials");
			}

			self::$connection =
					new mysqli(self::$host, self::$username, self::$password, self::$schema);
			self::$connection->set_charset(self::$charset);
		}
		return self::$connection;
	}

	/**
	 * Non-instantiable
	 */
	private function __construct() { }
}

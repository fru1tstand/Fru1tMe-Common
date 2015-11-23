<?php
namespace common\mysql;
use mysqli_sql_exception;
use mysqli_stmt;

/**
 * Provides convenience methods to handle a prepared MySQLi statement
 *
 * @version 0.1
 */
class QueryResult {
	private $stmt;

	public function __construct(mysqli_stmt $stmt) {
		$this->stmt = $stmt;
	}

	/**
	 * Returns whether or not the statement had more than 0 affected rows and closes
	 * the statement.
	 * @return boolean
	 */
	public function didAffectRows() {
		$res = $this->stmt->affected_rows > 0;
		$this->stmt->close();
		return $res;
	}

	/**
	 * Calls the passed method for each row the statement produced.
	 * @param callable $doFn
	 * @throws mysqli_sql_exception If no rows returned
	 * @return boolean
	 */
	public function forEachResult(callable $doFn) {
		$result = $this->stmt->get_result();
		if ($result->num_rows < 1)
			throw new mysqli_sql_exception("No rows returned from the query: " . $this->stmt);

		while ($row = $result->fetch_assoc())
			$doFn($row);

		return true;
	}
}

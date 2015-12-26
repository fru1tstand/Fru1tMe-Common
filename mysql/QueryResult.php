<?php
namespace common\mysql;
use mysqli_stmt;

/**
 * Handles MySQLi statements that have been executed against a database. Wraps common procedures
 * like iterating over result sets or returning single value result sets.
 *
 * @version 0.2
 */
class QueryResult {
	private $stmt;

	public function __construct(mysqli_stmt $stmt) {
		$this->stmt = $stmt;
	}

	/**
	 * Returns whether or not the statement had more than 0 affected rows and closes
	 * the statement.
	 * @return bool
	 */
	public function didAffectRows(): bool {
		$res = $this->stmt->affected_rows > 0;
		$this->stmt->close();
		return $res;
	}

	/**
	 * Calls the passed method for each row the statement produced. Returns false if no rows
	 * returned.
	 *
	 * @param callable $doFn
	 * @return bool
	 */
	public function forEachResult(callable $doFn): bool {
		$result = $this->stmt->get_result();
		$this->stmt->close();

		if ($result->num_rows < 1) {
			return false;
		}

		while ($row = $result->fetch_assoc()) {
			$doFn($row);
		}

		return true;
	}

	/**
	 * Used for single-column, single-row lookup queries. Returns the value obtained from the query.
	 * Returns null if 0 or more than 1 row resulted from the query. Returns the first column value
	 * if multiple columns were defined within the query.
	 *
	 * @return string | null
	 */
	public function getResultValue() {
		$result = $this->stmt->get_result();
		$this->stmt->close();

		if ($result->num_rows != 1) {
			return null;
		}

		$row = $result->fetch_row();
		return $row[0];
	}

	/**
	 * Used for single-row lookup queries. Returns all column values obtained from the query in an
	 * associative array mapping column name to values. Returns null if 0 or more than 1 row
	 * resulted from the query.
	 *
	 * @return array | null
	 */
	public function getResultValues() {
		$result = $this->stmt->get_result();
		$this->stmt->close();

		if ($result->num_rows != 1) {
			return null;
		}

		return $result->fetch_assoc();
	}
}

<?php
namespace me\fru1t\common\mysql;
use mysqli_stmt;

/**
 * Wraps mysqli_stmt objects that have already been executed. Provides methods to manipulate output
 * (for SELECT queries).
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
	 * Invokes the given callable, passing a single row as the sole parameter. Iterates through all
   * results in the order that it was received (respecting the ORDER BY clause). Returns true if
   * there are result rows. Otherwise, returns false.
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
	 * @return string|null
	 */
	public function getResultValue(): ?string {
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
	 * @return array|null
	 */
	public function getResultValues(): ?array {
		$result = $this->stmt->get_result();
		$this->stmt->close();

		if ($result->num_rows != 1) {
			return null;
		}

		return $result->fetch_assoc();
	}
}

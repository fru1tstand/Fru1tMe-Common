<?php
namespace me\fru1t\common\mysql;
use Exception;
use mysqli;
use mysqli_sql_exception;
use ReflectionClass;

/**
 * A builder for mysql queries which enforces the use of prepared statements.
 */
class QueryBuilder {
	const PARAM_TYPE_STRING = 's';
	const PARAM_TYPE_INT = 'i';
	const PARAM_TYPE_DOUBLE = 'd';
	const PARAM_TYPE_BLOB = 'b';

	const PARAM_STORE_VALUE = 0;
	const PARAM_STORE_TYPE = 1;

	private $queryString;
	private $queryParams;
	private $sqlConnectionReference;

	/**
	 * Creates a new QueryBuilder object from the given mysqli instance.
	 * @param mysqli $conn
	 */
	public function __construct(mysqli $conn) {
		$this->queryString = null;
		$this->queryParams = array();
		$this->sqlConnectionReference = $conn;
	}

	/**
	 * An sql query in the form of a prepared statement. Most notably, the replacement of parameters
	 * with question marks (?). See link for more details.
	 * @see http://php.net/manual/en/mysqli.prepare.php
	 * @param string $queryString
   * @return QueryBuilder this
	 */
	public function withQuery(string $queryString): self {
		$this->queryString = $queryString;
		return $this;
	}

	/**
	 * Specifies a parameter. Parameters must be given in the order they appear in the SQL query.
	 * @param mixed $paramValue
	 * @param string $paramType Use QueryBuilder::PARAM_TYPE_*
   * @return QueryBuilder this
	 */
	public function withParam($paramValue, string $paramType): self {
		$this->queryParams[] = array(
				self::PARAM_STORE_VALUE => $paramValue,
				self::PARAM_STORE_TYPE => $paramType
		);
		return $this;
	}

  /**
   * Specifies a string parameter. Parameters must be given in the order they appear in the SQL
   * query.
   * @param string $s
   * @return QueryBuilder
   */
	public function withStringParam(string $s): self {
	  return $this->withParam($s, self::PARAM_TYPE_STRING);
  }

  /**
   * Specifies an int parameter. Parameters must be given in the order they appear in the SQL query.
   * @param int $i
   * @return QueryBuilder
   */
  public function withIntParam(int $i): self {
	  return $this->withParam($i, self::PARAM_TYPE_INT);
  }

  /**
   * Specifies a double parameter. Parameters must be given in the order they appear in the SQL
   * query.
   * @param float $d
   * @return QueryBuilder
   */
  public function withDoubleParam(double $d): self {
    return $this->withParam($d, self::PARAM_TYPE_DOUBLE);
  }

  /**
   * Specifies a blob parameter. Parameters must be given in the order they appear in the SQL query.
   * @param string $s
   * @return QueryBuilder
   */
  public function withBlobParam(string $s): self {
    return $this->withParam($s, self::PARAM_TYPE_BLOB);
  }

  /**
   * Specifies a parameter that defines a string for a "LIKE" clause, padding it with the "%"
   * wildcard on both ends (zero or more of any character).
   * @param string $likeParamValue
   * @return QueryBuilder this
   */
	public function withLikeParam(string $likeParamValue): self {
	  return $this->withParam("&$likeParamValue%", self::PARAM_TYPE_STRING);
  }

	/**
	 * Prepares the query, bind the parameters, and spits out a QueryResult
	 * @throws Exception If the query string was not set
	 * @throws mysqli_sql_exception If the statement could not prepared
	 * @return QueryResult
	 */
	public function build(): QueryResult {
		if (is_null($this->queryString) || trim($this->queryString) === '') {
			throw new Exception('The query string was never set');
		}

		$stmt = $this->sqlConnectionReference->prepare($this->queryString);
		if (!$stmt) {
			throw new mysqli_sql_exception('Could not prepare statement: '
					. $this->sqlConnectionReference->error);
		}

		if (count($this->queryParams) > 0) {
			// Store what would be the arguments of #bind_param in an array where the
			// first element is the string of the types the parameters will be.
			$paramArray = array('');
			for ($i = 0; $i < count($this->queryParams); $i++) {
				$paramArray[0] .= $this->queryParams[$i][QueryBuilder::PARAM_STORE_TYPE];
				$paramArray[] = &$this->queryParams[$i][QueryBuilder::PARAM_STORE_VALUE];
			}

			// Create a reflection class to dynamically invoke #bind_param as
			// it does not natively support being passed an array of parameters
			// 'nor does it allow binding 1 parameter at a time.
			$mysqliReflectionClass = new ReflectionClass('mysqli_stmt');
			$bindParamMethod = $mysqliReflectionClass->getMethod('bind_param');
			$bindParamMethod->invokeArgs($stmt, $paramArray);
		}

		$stmt->execute();
		return new QueryResult($stmt);
	}
}

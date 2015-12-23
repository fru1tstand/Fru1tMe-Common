<?php
namespace common\ppunit;
use Exception;

/**
 * Provides a very lightweight testing framework
 * @package common\ppunit
 */
abstract class UnitTest {
	protected static function assertTrue(bool $logic, string $comment = null) {
		if (!$logic) {
			throw new Exception("True assert failed: $comment");
		}
		if (!is_null($comment)) {
			echo "<div>True assert passed: $comment</div>";
		}
	}

	protected static function assertFalse(bool $logic, string $comment = null) {
		if ($logic) {
			throw new Exception("False assert failed: $comment");
		}

		if (!is_null($comment)) {
			echo "<div>False assert passed: $comment</div>";
		}
	}

	protected static function assertEqual($obj1, $obj2, string $comment = null) {
		if ($obj1 != $obj2) {
			throw new Exception("Equality assert failed: $comment");
		}

		if (!is_null($comment)) {
			echo "<div>Equality assert passed: $comment</div>";
		}
	}

	protected static function addMessage(string $message) {
		echo "<div style='color: #99F;'>$message</div>";
	}
}

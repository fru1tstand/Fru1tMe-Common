<?php
namespace me\fru1t\common\language;

/**
 * A simple php object that contains a boolean and string to denote method return success and a
 * status message.
 */
class ReturnContext {
  /** @var boolean */
  private $didSucceed;
  /** @var null|string */
  private $message;

  public function __construct(bool $didSucceed, ?string $message = null) {
    $this->didSucceed = $didSucceed;
    $this->message = $message;
  }

  /**
   * Returns whether or not the call to the function succeeded.
   * @return bool
   */
  public function didSucceed(): bool {
    return $this->didSucceed;
  }

  /**
   * Returns the call's context message. Always returns a string, even an empty string if the
   * status wasn't given by the returning function.
   * @return string
   */
  public function getMessage(): string {
    return $this->message ?? '';
  }
}

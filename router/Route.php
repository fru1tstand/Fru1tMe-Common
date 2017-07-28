<?php
namespace me\fru1t\common\router;
use me\fru1t\common\language\Preconditions;

/**
 * Routes output data if the given url matches the activation path.
 */
abstract class Route {
  /** @var string[] */
  private $headers;

  /**
   * Route constructor.
   * @param string[] $headers An array of PHP headers to prepend to the payload on resolution.
   */
  protected function __construct(array $headers = []) {
    $this->headers = $headers;
  }

  /**
   * Retrieves the headers for this route, if any were specified.
   * @return string[]
   */
  public function getHeaders(): array {
    return $this->headers;
  }

  /**
   * Checks this route to see if the request matches this route. Executes the route payload if a
   * match occurs, and returns whether this route matched the given url.
   * @param string $url
   * @return bool Whether or not this route matched the url.
   */
  public function execute(?string $url): bool {
  	if ($url == null) {
  		return false;
	}

    if ($this->matches($url)) {
      foreach ($this->headers as $header) {
        if (!Preconditions::isNullEmptyOrWhitespace($header)) {
          header($header);
        }
      }
      $this->resolve();
      return true;
    }
    return false;
  }

  /**
   * Outputs this route's payload via echo, include, print, etc.
   */
  protected abstract function resolve(): void;

  /**
   * Queries and returns if the route matches for the given url.
   * @param string $url
   * @return bool
   */
  protected abstract function matches(string $url): bool;
}

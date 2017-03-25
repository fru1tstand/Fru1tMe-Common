<?php
namespace me\fru1t\common\router\route;
use me\fru1t\common\router\Route;

/**
 * Abstracts a static route that maps a single unchanging url to something.
 */
abstract class StaticRoute extends Route {
  /** @var string */
  private $requestUrl;
  /** @var bool */
  private $isCaseSensitive;

  protected function __construct(string $requestUrl, bool $isCaseSensitive = true, $headers = []) {
    parent::__construct($headers);
    $this->requestUrl = $isCaseSensitive ? $requestUrl : strtolower($requestUrl);
    $this->isCaseSensitive = $isCaseSensitive;
  }

  /**
   * Queries and returns if the route matches for the given url.
   * @param string $url
   * @return bool
   */
  protected function matches(string $url): bool {
    if ($this->isCaseSensitive) {
      return ($url === $this->requestUrl);
    } else {
      return (strtolower($url) === $this->requestUrl);
    }
  }

  /**
   * Retrieves the request URL this route will respond to.
   * @return string
   */
  public function getRequestUrl(): string {
    return $this->requestUrl;
  }

  /**
   * Retrieves if this route is case sensitive.
   * @return bool
   */
  public function isCaseSensitive(): bool {
    return $this->isCaseSensitive;
  }
}

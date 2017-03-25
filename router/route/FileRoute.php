<?php
namespace me\fru1t\common\router\route;

/**
 * Defines a single static route that maps a single input to a single file.
 */
class FileRoute extends StaticRoute {
  /** @var string */
  private $responsePath;
  /** @var string */
  private $requestUrl;
  /** @var bool */
  private $isCaseSensitive;

  /**
   * Creates a file router that maps a url to a file on disk.
   * @param string $requestUrl The request string to catch. The request should omit the domain name
   *     and trailing slash. For example, to handle "http://example.com/thing.ext", one would set
   *     the request to "thing.ext". This path is case sensitive.
   * @param string $responsePath The path and filename of the file to serve on local disk.
   * @param bool $isCaseSensitive Optional Whether or not the request url should be case sensitive.
   *     This defaults to true.
   * @param string[] $headers Optional An array of PHP headers to prepend to the payload upon route
   *     resolution.
   */
  public function __construct(
      string $requestUrl,
      string $responsePath,
      bool $isCaseSensitive = true,
      array $headers = []) {
    parent::__construct($requestUrl, $isCaseSensitive, $headers);
    $this->responsePath = $responsePath;
    $this->isCaseSensitive = $isCaseSensitive;
  }

  /**
   * Retrieves the response file path.
   * @return string
   */
  public function getResolve(): string {
    return $this->responsePath;
  }

  /**
   * Outputs this route's payload via echo, include, print, etc.
   */
  protected function resolve(): void {
    /** @noinspection PhpIncludeInspection */
    include($this->responsePath);
  }
}

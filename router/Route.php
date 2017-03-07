<?php
namespace me\fru1t\common\router;
use me\fru1t\common\language\Preconditions;
use RuntimeException;

/**
 * Defines a static route
 */
class Route {
  /**
   * Creates a new instance of Route.
   * @return Route
   */
  public static function newBuilder(): Route {
    return new Route();
  }

  /**
   * Creates and builds a route given a request and location.
   * @param string $request The request string to catch. The request should omit the domain name and
   *     and trailing slash. For example, to handle "http://example.com/thing.ext", one would set
   *     the request to "thing.ext"
   * @param string $location The path and filename of the file to serve.
   * @param null|string $header Optional headers to send with the file.
   * @return Route A built route object.
   */
  public static function create(string $request, string $location, $header = null): Route {
    $route = new Route();
    $route->whenRequested($request)->provide($location);
    if (!Preconditions::isNullEmptyOrWhitespace($header)) {
      $route->withHeader($header);
    }
    return $route->build();
  }

  /**
   * Use {@link Route::newBuilder()}
   */
  private function __construct() {
    $this->request = null;
    $this->resolve = null;
    $this->isBuilt = false;
    $this->header = null;
  }

  /** @var null|string */
  private $request;
  /** @var null|string */
  private $resolve;
  /** @var null|string */
  private $header;
  /** @var bool */
  private $isBuilt;

  /**
   * Returns whether or not this route has been built.
   * @return bool
   */
  public function isBuilt(): bool {
    return $this->isBuilt;
  }

  /**
   * @return null|string
   */
  public function getRequest(): ?string {
    return $this->request;
  }

  /**
   * @return null|string
   */
  public function getResolve(): ?string {
    return $this->resolve;
  }

  /**
   * Specifies the URL with which to activate this route for. Do not include a leading or trailing
   * slash.
   * @param string $address
   * @return Route
   */
  public function whenRequested(string $address): Route {
    $this->request = $address;
    return $this;
  }

  /**
   * Specifies the file to provide.
   * @param string $filePath The file path, relative to web root (DOCUMENT_ROOT). Allows the use of
   * parent directory paths (eg. "../some/file.txt").
   * @return Route
   */
  public function provide(string $filePath): Route {
    $this->resolve = $filePath;
    return $this;
  }

  /**
   * Sets a single optional header to send with the given static content.
   * @param string $header
   * @return Route
   */
  public function withHeader(string $header): Route {
    $this->header = $header;
    return $this;
  }

  /**
   * Validates and returns this Route for use in the Router.
   * @return Route
   */
  public function build(): Route {
    if (!Preconditions::isFile($this->resolve)) {
      throw new RuntimeException("The file '{$this->resolve}' doesn't exist.");
    }
    $this->isBuilt = true;
    return $this;
  }

  /**
   * Checks this route to see if the file requested is this route. If the match succeeds, it will
   * include the resolve file and completely exit PHP execution.
   * @param $fileRequested
   */
  public function navigate($fileRequested): void {
    if ($this->request === $fileRequested) {
      if (!Preconditions::isNullEmptyOrWhitespace($this->header)) {
        header($this->header);
      }
      /** @noinspection PhpIncludeInspection */
      include($this->resolve);
      exit(0);
    }
  }
}

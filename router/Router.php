<?php
namespace me\fru1t\common\router;
use me\fru1t\common\language\Http;
use me\fru1t\common\language\Param;
use me\fru1t\common\language\Preconditions;
use RuntimeException;

/**
 * <p>Handles URL routing. This router depends on a central routing file (usually index.php in the
 * website's root), that all requests will go through. See readme for a more comprehensive
 * explanation.
 */
final class Router {
  /**
   * The default parameter name to specify the content page requested.
   */
  const DEFAULT_PAGE_PARAMETER_NAME = "page";

  /**
   * The default value for allowing the format parameter to be a GET request.
   */
  const DEFAULT_ALLOW_FORMAT_PARAMETER_AS_GET = true;

  /** @var Router|null The set up router instance. */
  private static $router = null;
  /** @var string|null */
  private static $rawRequestedPage = null;
  /** @var int */
  private static $htmlReturnCode = Http::STATUS_OK;

  /**
   * Starts the setup process for the Router. See the readme for more details on options.
   *
   * @return Router
   */
  public static function setup(): Router {
    return new Router();
  }

  /**
   * Starts the routing process. This requires that the router has been configured via
   * {@link Router:setup()} which sets up routers and requested page.
   */
  public static function route() {
    if (!self::isSetup()) {
      throw new RuntimeException("The router was never set up.");
    }

    // Check for a request page
    if (Preconditions::isNull(Param::fetchGet(self::$router->pageParameterName))) {
      self::openContentFileFromPath(self::$router->defaultContentPagePath);
      return;
    }

    // Requests can only be alpha-numeric with underscores or dashes, or folders. This prevents
    // leaking (via /.. requests).
    $cleanPageRequest = preg_replace('/[^0-9a-zA-Z%\/_-]/', "", self::$rawRequestedPage);

    // Try exact location
    $attempt = $cleanPageRequest . ".php";
    if (self::openContentFileFromPath($attempt)) {
      return;
    }

    // Try as folder with an index.php
    $attempt = $cleanPageRequest . "/index.php";
    if (self::openContentFileFromPath($attempt)) {
      return;
    }

    // Otherwise 404
    self::$htmlReturnCode = Http::STATUS_NOT_FOUND;
    self::openContentFileFromPath(self::$router->errorPagePath);
  }

  /**
   * @param string $path
   * @return bool
   */
  private static function openContentFileFromPath(string $path): bool {
    if (!is_file(self::$router->contentDir . $path)) {
      return false;
    }

    /** @noinspection PhpIncludeInspection */
    require(self::$router->contentDir . $path);
    return true;
  }

  private static function isSetup(): bool {
    return !Preconditions::isNull(self::$router);
  }

  /**
   * Use {@link Router::setup()}.
   */
  private function __construct() {
    $this->contentDir = null;
    $this->errorPagePath = null;
    $this->defaultContentPagePath = null;
    $this->pageParameterName = null;
    $this->routes = [];
  }

  /** @var string|null */
  private $contentDir;
  /** @var string|null */
  private $errorPagePath;
  /** @var string|null */
  private $defaultContentPagePath;
  /** @var string|null */
  private $pageParameterName;
  /** @var Route[] */
  private $routes;

  /**
   * <p>Sets where the Router will look for content files in. This folder should exclusively only
   * contain content files and should not be web-accessible (along with all other PHP files). See
   * the associated readme for more details on project setup.
   *
   * <p>Pass path as a relative folder location from document root (not project root) without a
   * trailing or prefixing slash. Parent directory paths are valid and encouraged (to remove the
   * path from the web root). Mine, for example, is "../php".
   *
   * @param string $path
   * @return Router
   */
  public function setContentDirectory(string $path): Router {
    $this->contentDir = $_SERVER['DOCUMENT_ROOT'] . "/" . $path . "/";
    return $this;
  }

  /**
   * Sets the default page to display when no page is specified via the page parameter.
   *
   * @param string $defaultContentPagePath The path of the default content page, relative to the
   *     content directory.
   * @return Router
   */
  public function setDefaultContentPagePath(string $defaultContentPagePath): Router {
    $this->defaultContentPagePath = $defaultContentPagePath;
    return $this;
  }

  /**
   * Sets the error page to display in case of client or server error (eg. 404 not found, etc).
   *
   * @param string $errorPagePath The path of the error page, relative to the content directory.
   * @return Router
   */
  public function setErrorPagePath(string $errorPagePath): Router {
    $this->errorPagePath = $errorPagePath;
    return $this;
  }

  /**
   * Sets the name of the parameter that the router uses to determine the page requested.
   *
   * @param string $pageParameterName
   * @return Router
   */
  public function setPageParameterName(string $pageParameterName): Router {
    $this->pageParameterName = $pageParameterName;
    return $this;
  }

  /**
   * Adds the given route to the router.
   *
   * @param Route $route
   * @return Router
   */
  public function map(Route $route): Router {
    if (!$route->isBuilt()) {
      throw new RuntimeException("The route mapping '{$route->getRequest()}' to "
          . "'{$route->getResolve()}' was never built.");
    }
    $this->routes[] = $route;
    return $this;
  }

  /**
   * Validate and completes setup. Also triggers static
   */
  public function complete() {
    if (Preconditions::isNullEmptyOrWhitespace($this->contentDir)
        || !Preconditions::isFolder($this->contentDir)) {
      throw new RuntimeException(
          "TemplateRouter couldn't find the content directory at " . $this->contentDir);
    }
    if (Preconditions::isNullEmptyOrWhitespace($this->errorPagePath)
        || !Preconditions::isFile($this->contentDir . $this->errorPagePath)) {
      throw new RuntimeException("TemplateRouter couldn't find the error page at: "
          . $this->contentDir . $this->errorPagePath);
    }
    if (Preconditions::isNullEmptyOrWhitespace($this->defaultContentPagePath)
        || !Preconditions::isFile($this->contentDir . $this->defaultContentPagePath)) {
      throw new RuntimeException("TemplateRouter couldn't find the default content page at: "
          . $this->contentDir . $this->defaultContentPagePath);
    }

    // Get requested page.
    self::$rawRequestedPage = Param::fetchGet($this->pageParameterName);

    // Check static map.
    foreach ($this->routes as $route) {
      $route->navigate(self::$rawRequestedPage);
    }

    self::$router = $this;
  }
}

<?php
namespace me\fru1t\common\template;
use me\fru1t\common\language\Param;
use me\fru1t\common\language\Preconditions;

/**
 * Controls the behavior of the routing table for this library.
 */
class TemplateRouter {
  /**
   * Used to specify returning the content in JSON form.
   */
	const FORMAT_JSON = "json";

  /**
   * The default parameter name to specify the content return type.
   */
  const DEFAULT_FORMAT_PARAMETER_NAME = "fmt";

  /**
   * The default parameter name to specify the content page requested.
   */
  const DEFAULT_PAGE_PARAMETER_NAME = "page";

  /**
   * The default value for allowing the format parameter to be a GET request.
   */
  const DEFAULT_ALLOW_FORMAT_PARAMETER_AS_GET = true;

  /** @var string|null */
  private static $contentDir = null;

  /** @var string|null */
  private static $errorPage = null;

  /** @var string|null */
  private static $defaultContentPage = null;

  /** @var int */
  private static $htmlReturnCode = 200;

  /** @var string|null */
  private static $requestedPage = null;

  /**
   * <p>Opens (via require, and thus, triggering its default rendering behavior) the content page
   * requested by a get parameter. Note that content page names may only be alphanumeric with
   * underscores and hyphens.
   *
   * <p>To pretty-fy URLs, it's common to use a .htaccess (or similar) file to route everything
   * through an index.php controller file, passing it parameters. The .htaccess keeps the URL in the
   * browser intact, while silently rerouting it. Using the default parameter names , an example
   * rewrite is:
   * <pre>
   *     example.com/foo/bar -> example.com/?page=foo/bar
   * </pre>
   * The default .htaccess file I use consists of the following lines:
   * <pre>
   *     # Turns on the rewrite plugin for Apache
   *     RewriteEngine On
   *
   *     # Ignores rewriting URLs pointed to files (eg. example.com/styles.css)
   *     RewriteRule ^.+\..+$ - [L,NC]
   *
   *     # Rewrites everything else to point to index.php, passing it everything after the slash as
   *     # a value to the parameter called "page".
   *     RewriteRule ^([^?.]+)(\?.+)?$ /index.php?page=$1 [L,QSA]
   * </pre>
   * The parameter "page" may be changed, but the name must be passed to this method as
   * $pageParameterName so that the templating system knows where to look.
   *
   * <p>Note: The index.php file is a great place to do all setups. *hint hint, autoloading, MySQL,
   * sessions, etc* :)
   *
   * @param string $contentPath The path to the content relative to the DOCUMENT_ROOT. This path can
   *     go backwards (eg. "/../../"). Please include a prefixing and trailing slash in the path.
   *     It's good practice to place this folder somewhere inaccessible to the public. I normally
   *     place mine at "/../php/<my project namespace>/content"
   * @param string $defaultContentPage The default content page file location. This page will appear
   *     if no page is requested. This should be relative to the $contentPath given. Do not include
   *     a prefixing slash.
   * @param string $errorPage The error page file location. This page will appear if the requesting
   *     page doesn't exist, or any other error occurs (server, permissions, HTTP errors, etc). This
   *     should be relative to the $contentPath given. Do not include a prefixing slash.
   * @param string $pageParameterName (optional) Defaults to
   *     {@link TemplateRouter::DEFAULT_PAGE_PARAMETER_NAME}. The parameter name that the .htaccess
   *     file rewrites a raw URL to.
   * @param string $formatParameterName (optional) Defaults to
   *     {@link TemplateRouter::DEFAULT_FORMAT_PARAMETER_NAME}. The parameter name that specifies
   *     which format to render the content in.
   * @param bool $allowFormatParameterAsGetRequest (optional) Defaults to
   *     {@link TemplateRouter::DEFAULT_ALLOW_FORMAT_PARAMETER_AS_GET}. Allows the format parameter
   *     to be sent as a GET request instead of solely a POST.
   */
	public static function openContentFileFromUrl (
			string $contentPath,
			string $defaultContentPage,
      string $errorPage,
      string $pageParameterName = self::DEFAULT_PAGE_PARAMETER_NAME,
      string $formatParameterName = self::DEFAULT_FORMAT_PARAMETER_NAME,
      bool $allowFormatParameterAsGetRequest = self::DEFAULT_ALLOW_FORMAT_PARAMETER_AS_GET) {
	  // Set up.
	  self::$contentDir = $_SERVER['DOCUMENT_ROOT'] . $contentPath;
    self::$defaultContentPage = self::$contentDir . $defaultContentPage;
    self::$errorPage = self::$contentDir . $errorPage;

	  // Check for correct setup
    if (!Preconditions::isFolder(self::$contentDir)) {
      throw new TemplateException(
          "TemplateRouter couldn't find the content directory at " . self::$contentDir);
    }
    if (!Preconditions::isFile(self::$errorPage)) {
      throw new TemplateException(
          "TemplateRouter couldn't find the error page at: " . self::$errorPage);
    }
    if (!Preconditions::isFile(self::$defaultContentPage)) {
      throw new TemplateException(
          "TemplateRouter couldn't find the default content page at: " . self::$defaultContentPage);
    }

		// Set/Check render format
		Template::setRenderFormat(Template::RENDER_FORMAT_ALL_HTML);
    $renderValue =
        (($allowFormatParameterAsGetRequest) ? Param::fetchGet($formatParameterName) : null)
        ?? Param::fetchPost($formatParameterName);
    if ($renderValue === self::FORMAT_JSON) {
      Template::setRenderFormat(Template::RENDER_FORMAT_CONTENT_ONLY_JSON);
      header('Content-Type: application/json');
    }

		// Check for a request page
		if (Preconditions::isNull(Param::fetchGet($pageParameterName))) {
      self::openContentFileFromPath(self::$defaultContentPage); // guaranteed to exist
      return;
		}

		// Requests can only be alpha-numeric with underscores or dashes, or folders. This prevents
    // leaking (via /.. requests).
    self::$requestedPage =
        preg_replace('/[^0-9a-zA-Z%\/_-]/', "", Param::fetchGet($pageParameterName));

		// Try exact location
		$attempt = self::$contentDir . self::$requestedPage . ".php";
		if (self::openContentFileFromPath($attempt)) {
			return;
		}

		// Try as folder with an index.php
		$attempt = self::$contentDir . self::$requestedPage . "/index.php";
		if (self::openContentFileFromPath($attempt)) {
			return;
		}

		// Otherwise 404
    self::$htmlReturnCode = 404;
    self::openContentFileFromPath(self::$errorPage); // Guaranteed to exist
	}

	/**
	 * @param string $path
	 * @return bool
	 */
	private static function openContentFileFromPath(string $path): bool {
		if (!is_file($path)) {
			return false;
		}

		/** @noinspection PhpIncludeInspection */
		require($path);
		return true;
	}
}

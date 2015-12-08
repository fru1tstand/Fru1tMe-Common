<?php
namespace common\template;
use common\template\component\TemplateException;

/**
 * Class TemplateUtils
 *
 * Content rendering utilities for the Fru1tMe templating engine.
 */
class TemplateUtils {
	/** Should be equal to the GET parameter set by the redirecting .htaccess from a mapped url.
	 * @see TemplateUtils::renderContentFromUrl */
	const PATH_GET_PARAMETER = "page";

	/** The default name for the default content page given a content path
	 * @see TemplateUtils::renderContentFromUrl */
	const DEFAULT_CONTENT_PAGE = "index.php";


	/** The parameter the templating system will look for when rendering to determine return format
	 * type. */
	const RETURN_FORMAT_PARAMETER = "fmt";

	/** Determines if the templating system will honor GET requests specifying JSON only responses
	 * vs solely POST requests. */
	const ALLOW_GET_RETURN_FORMAT_PARAMETER = true;

	/** The value that the RETURN_FORMAT_PARAMETER needs to be for the templating engine to respond
	 * with json. */
	const RETURN_FORMAT_JSON = "json";


	/**
	 * <p>Returns content given a path and default page from the current URL using
	 * {@link TemplateUtils::PATH_GET_PARAMETER} as the parameter key to the path of the requested
	 * content. An .htaccess file (or equivalent) should be used to silently rewrite the url.
	 *
	 * <p>An example rewrite is:
	 * 		http://example.com/foo/bar?bizz=buzz -> http://example.com/?PATH_GET_PARAMETER=foo/bar&bizz=buzz
	 *
	 * <p>The default .htaccess file I use is
	 * 		RewriteRule ^([^?.]+)(\?.+)?$ /index.php?page=$1 [L,QSA]
	 * setting the parameter value to 'page'.
	 *
	 * <p>This parameter is then used as a directory listing from the given contentPath.
	 * Eg. Using the URL above and $contentPath set to
	 * 		{$_SERVER['DOCUMENT_ROOT']}/.site/php/example/html/content
	 * this method will attempt to find these pages (in order of priority):
	 * 		{$_SERVER['DOCUMENT_ROOT']}/.site/php/example/html/content/foo/bar.php
	 * 		{$_SERVER['DOCUMENT_ROOT']}/.site/php/example/html/content/foo/bar/index.php
	 *
	 * @param string $contentPath The path to the content (do not include a trailing slash).
	 * @param string $defaultContentPagePath The default content page path to use, relative to the
	 * contentPath given.
	 *
	 * @throws TemplateException Thrown if the default content page couldn't be found.
	 */
	public static function renderContentFromUrl (
			string $contentPath,
			string $defaultContentPagePath = self::DEFAULT_CONTENT_PAGE) {

		// Set render format defaulting to standard HTML if not specified
		Content::setRenderFormat(Content::RENDER_FORMAT_ALL_HTML);
		if (self::ALLOW_GET_RETURN_FORMAT_PARAMETER
				&& isset($_GET[self::RETURN_FORMAT_PARAMETER])
				&& $_GET[self::RETURN_FORMAT_PARAMETER] == self::RETURN_FORMAT_JSON) {
			Content::setRenderFormat(Content::RENDER_FORMAT_CONTENT_ONLY_JSON);
			header('Content-Type: application/json');
		}

		$contentPath .= "/";

		// If no page, go default
		if (!isset($_GET[self::PATH_GET_PARAMETER])) {
			self::renderDefaultContentPage($contentPath . $defaultContentPagePath);
			return;
		}

		$request = preg_replace('/[^0-9a-zA-Z%\/_-]/', "", $_GET[self::PATH_GET_PARAMETER]);

		// Try exact location
		$attempt = $contentPath . $request . ".php";
		if (self::renderContentFromFilePath($attempt)) {
			return;
		}

		// Try as folder with an index.php
		$attempt = $contentPath . $request . "/index.php";
		if (self::renderContentFromFilePath($attempt)) {
			return;
		}

		self::renderDefaultContentPage($contentPath . $defaultContentPagePath);
	}

	/**
	 * @param string $path
	 * @return bool
	 * @throws TemplateException
	 */
	private static function renderContentFromFilePath(string $path): bool {
		if (!is_file($path)) {
			return false;
		}

		/** @noinspection PhpIncludeInspection */
		require($path);
		return true;
	}

	/**
	 * @param string $pagePath
	 * @throws TemplateException
	 */
	private static function renderDefaultContentPage(string $pagePath) {
		if (!self::renderContentFromFilePath($pagePath)) {
			throw new TemplateException("The default template failed to load.");
		}
	}
}

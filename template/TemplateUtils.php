<?php
namespace common\template;

/**
 * Class PageHandler
 *
 * This class handles page requests through url.
 */
class TemplateUtils {
	/**
	 * Attempts to render the GET page request and falls back to the default if not found.
	 *
	 * @param $contentLocation
	 * @param $defaultContentLocation
	 * @throws TemplateException
	 */
	public static function renderRequestedContentPage($contentLocation, $defaultContentLocation) {
		$contentLocation .= "/";

		// If no page, go default
		if (!isset($_GET['page'])) {
			if (!self::renderContentFromFilePath($contentLocation . $defaultContentLocation)) {
				throw new TemplateException("The default template failed to load.");
			}
			return;
		}

		$request = preg_replace('/[^0-9a-zA-Z%\/_-]/', "", $_GET['page']);

		// Try exact location
		$attempt = $contentLocation . $request . ".php";
		if (self::renderContentFromFilePath($attempt)) {
			return;
		}

		// Try as folder with an index.php
		$attempt = $contentLocation . $request . "/index.php";
		if (self::renderContentFromFilePath($attempt)) {
			return;
		}

		if (!self::renderContentFromFilePath($contentLocation . $defaultContentLocation)) {
			throw new TemplateException("The default template failed to load.");
		}
	}

	private static function renderContentFromFilePath($path) {
		if (!is_file($path)) {
			return false;
		}

		/** @noinspection PhpIncludeInspection */
		require($path);
		return ContentPage::execute();
	}
}

<?php
namespace common\template;
use common\template\ContentPage;

/**
 * Class PageHandler
 * @package common\template
 *
 * This class handles page requests through url.
 */
class TemplateUtils {
	/**
	 * Renders the requested page via page post, or the default content if invalid or not given.
	 *
	 * @param $contentLocation
	 * @param $defaultContentLocation
	 */
	public static function renderRequestedContentPage($contentLocation, $defaultContentLocation) {
		$contentLocation .= "/";

		if (!isset($_GET['page'])) {
			self::renderContentFromFilePath($defaultContentLocation);
			return;
		}

		$request = preg_replace('/[^0-9a-zA-Z/%_-]/', "", $_GET['page']);

		// Try exact location
		if (is_file($contentLocation . $request . ".php")) {
			self::renderContentFromFilePath($request);
			return;
		}

		// Try as folder
		if (is_file($contentLocation . $request . "/index.php")) {
			self::renderContentFromFilePath($request . "/index");
			return;
		}

		self::renderContentFromFilePath($defaultContentLocation);
	}

	private static function renderContentFromFilePath($request) {

	}
}

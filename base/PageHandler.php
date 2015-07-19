<?php
namespace common\base;

/**
 * Class PageHandler
 * @package common\template
 *
 * This class handles page requests through url.
 */
abstract class PageHandler {
	/**
	 * Returns the absolute path of the pages folder as a string.
	 * @return string
	 */
	protected abstract function getPageFolderPath();

	/**
	 * Returns the absolute path of the default page to display in the event the requested page
	 * is invalid or does not exist.
	 * @return string
	 */
	protected abstract function getDefaultPagePath();

	/**
	 * Gets the absolute path of the page requested via URL. If the path requested is invalid
	 *  is returned instead.
	 * @return string
	 */
	public function getRequestedPageLocation() {
		if (!isset($_GET['page']))
			return $this->getDefaultPagePath();
		$request = preg_replace('/[^0-9a-zA-Z/%_-]/', "", $_GET['page']);

		// Try exact location
		$attempt = $this->getFullPathTo($request) . ".php";
		if (is_file($attempt))
			return $attempt;

		// Try as folder
		$attempt = $this->getFullPathTo($request) . "home.php";
		if (is_file($attempt))
			return $attempt;

		return $this->getDefaultPagePath();
	}

	/**
	 * Convenience method to return the full string path to the passed page.
	 * @param $name
	 * @return string
	 */
	private function getFullPathTo($name) {
		return $this->getPageFolderPath() . $name;
	}
}
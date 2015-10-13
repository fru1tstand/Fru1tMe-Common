<?php
namespace common\template;
require_once $_SERVER['DOCUMENT_ROOT'] . '/.site/php/fru1tme/Setup.php';

/**
 * TemplateInterface
 */
interface TemplateInterface {
	/**
	 * INTERNAL USE. Unless you know what you're doing, call Template::render(ContentPage) instead.
	 * This method should return the fully populated template page.
	 *
	 * @param $fields string[] All fields in an associative array, requested by this page.
	 * @return string
	 */
	public static function getRenderContents($fields);

	/**
	 * Returns the fields for this template.
	 *
	 * @return string[]
	 */
	public static function getFields();

	/**
	 * Returns the fully qualified class name.
	 *
	 * @return string
	 */
	public static function getClass();
}

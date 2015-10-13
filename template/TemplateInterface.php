<?php
namespace common\template;
require_once $_SERVER['DOCUMENT_ROOT'] . '/.site/php/fru1tme/Setup.php';

/**
 * TemplateInterface
 */
interface TemplateInterface {
	/**
	 * Gets the object instance for this template.
	 */
	public static function getInstance();
}

<?php
namespace common\template\component;
use common\template\Content;

require_once $_SERVER['DOCUMENT_ROOT'] . '/.site/php/csgoderank/Setup.php';

/**
 * Class TemplateInterface
 */
interface TemplateInterface {
	/**
	 * Produces the complete HTML string for this content page given content fields for this page.
	 *
	 * @param ContentField[] $fields An associative array mapping fields to ContentField objects.
	 * @return string
	 */
	public static function getTemplateRendering(array $fields): string;

	/**
	 * Returns the TemplateField objects associated to this content page. These are the fields that
	 * are used within the template rendering method.
	 *
	 * @return TemplateField[]
	 */
	public static function getTemplateFields(): array;

	/**
	 * Creates a new content page for this content template.
	 *
	 * @return Content
	 */
	public static function create(): Content;
}

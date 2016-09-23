<?php
namespace me\fru1t\common\template;

/**
 * Interface TemplateInterface
 */
interface TemplateInterface {
	/**
	 * Produces the content this template defines in the form of an HTML string. This method is passed
   * a map with template field names as keys, and values that the content page provides.
	 *
	 * @param string[] $fields An associative array mapping fields to ContentField objects.
	 * @return string
	 */
	public static function getTemplateRenderContents_internal(array $fields): string;

	/**
	 * Provides the fields this template contains.
	 *
	 * @return TemplateField[]
	 */
	static function getTemplateFields_internal(): array;
}

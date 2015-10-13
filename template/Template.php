<?php
namespace common\template;
require_once $_SERVER['DOCUMENT_ROOT'] . '/.site/php/fru1tme/Setup.php';

/**
 * Class Template
 * Defines a template for content pages to populate.
 */
abstract class Template implements TemplateInterface {
	/**
	 * Attempts to render the given contents with this template.
	 *
	 * @param ContentPage $content
	 * @throws TemplateException
	 */
	public static function render(ContentPage $content) {
		if (!($content->getTemplate() instanceof self)) {
			throw new TemplateException(
					"A mismatch between template and content occurred. "
					. "Please use ContentPage->render().");
		}
	}

	/**
	 * Returns if the given field is a valid one within this template.
	 *
	 * @param $field
	 * @return bool
	 */
	public function isField($field) {
		return isset($this->getFields()[$field]);
	}

	protected abstract function getFields();

	protected abstract function getRenderContents($fields);
}

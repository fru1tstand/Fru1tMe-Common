<?php
namespace common\template;
require_once $_SERVER['DOCUMENT_ROOT'] . '/.site/php/fru1tme/Setup.php';

/**
 * Class ContentPage
 */
class ContentPage {
	/** @var $template Template */
	private $template;
	/** @var $fields string[] */
	private $fields;

	/**
	 * Creates a new content page instance with the given template driver.
	 * @param Template $template
	 */
	public function __construct(Template $template) {
		$this->template = $template;
	}

	/**
	 * Sets the given field for this content page with content.
	 *
	 * @param string|int $field The field to populate.
	 * @param string $content The content to save.
	 * @return $this This for daisy chaining
	 * @throws TemplateException If the field is already set or invalid.
	 */
	public function setField($field, $content) {
		if (isset($this->fields[$field])) {
			throw new TemplateException("$field is already set for this content page");
		}

		if (!$this->template->isField($field)) {
			throw new TemplateException("$field isn't a field within "
					. get_class($this->template));
		}

		$this->fields[$field] = $content;
		return $this;
	}

	/**
	 * Returns whether or not this content page has the given field.
	 *
	 * @param $field
	 * @return bool
	 */
	public function hasField($field) {
		return isset($this->fields[$field]);
	}

	/**
	 * Fetches the given field for this
	 * @param string|int $field The field to get.
	 * @return string The field contents or an empty string.
	 * @throws TemplateException If the field is already set or invalid.
	 */
	public function getFieldContent($field) {
		if (!$this->template->isField($field)) {
			throw new TemplateException("$field isn't a field within "
					. get_class($this->template));
		}

		return isset($this->fields[$field]) ? $this->fields[$field] : "";
	}

	/**
	 * Fetches this content page's driving template.
	 *
	 * @return Template
	 */
	public function getTemplate() {
		return $this->template;
	}

	/**
	 * Renders this content page using its template.
	 */
	public function render() {
		$this->template->render($this);
	}
}

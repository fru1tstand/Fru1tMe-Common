<?php
namespace common\template;
require_once $_SERVER['DOCUMENT_ROOT'] . '/.site/php/fru1tme/Setup.php';

/**
 * Class ContentPageBuilder
 */
class ContentPageBuilder {
	/**
	 * Creates a new content page builder with the given template.
	 *
	 * @param string $template The template class name to use.
	 * @return ContentPageBuilder
	 */
	public static function ofTemplate($template) {
		return new ContentPageBuilder($template);
	}

	/** @var string[] */
	private $fields;
	/** @var string */
	private $template;

	private function __construct($template) {
		$this->fields = [];
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

		$this->fields[$field] = $content;
		return $this;
	}

	/**
	 * Creates and returns a new content page with the builder. Alternatively, one can call
	 * #register to automatically call ContentPage#register for the current builder.
	 *
	 * @return ContentPage
	 */
	public function build() {
		return new ContentPage($this->template, $this->fields);
	}

	/**
	 * Builds this content page builder and registers the result within ContentPage.
	 */
	public function register() {
		ContentPage::register($this->build());
	}
}
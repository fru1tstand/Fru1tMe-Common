<?php
namespace common\template;
use common\template\component\ContentField;
use common\template\component\TemplateException;
use common\template\component\TemplateInterface;

/**
 * Defines the starter for a content/template page.
 */
abstract class Content implements TemplateInterface {
	/** @type ContentField[] */
	private $contentFields;

	protected function __construct() {
		$this->contentFields = [];
		foreach (static::getTemplateFields() as $templateField) {
			$this->contentFields[$templateField->getName()] = $templateField->newContentField();
		}
	}

	/**
	 * Specifies content for a given field for this content page.
	 *
	 * @param string $field
	 * @param string $content
	 * @return Content
	 * @throws TemplateException
	 */
	public function with(string $field, string $content): Content {
		if (!isset($this->contentFields[$field])) {
			throw new TemplateException("$field doesn't exist in this template");
		}
		if (isset($this->contentFields[$field])) {
			throw new TemplateException("$field is already set for this content page");
		}

		$this->contentFields[$field]->setContent($content);
		return $this;
	}

	/**
	 * Returns the template HTML populated with this content page.
	 *
	 * @return string
	 */
	public function getRenderContents(): string {
		return static::getTemplateRendering($this->contentFields);
	}

	/**
	 * Renders the HTML created by this content page from the controlling template.
	 */
	public function render() {
		echo $this->getRenderContents();
	}
}

<?php
namespace common\template\component;

/**
 * Class ContentField
 */
class ContentField {
	/**
	 * Creates a ContentField given a TemplateField.
	 *
	 * @param TemplateField $templateField
	 * @return ContentField
	 */
	public static function of(TemplateField $templateField): ContentField {
		return new ContentField($templateField);
	}


	/** @type TemplateField */
	private $templateField;
	/** @type string */
	private $content;

	/**
	 * Use {@link ContentField::newBuilder}.
	 *
	 * @param TemplateField $templateField
	 * @param string $content
	 * @internal
	 */
	public function __construct(TemplateField $templateField, string $content = null) {
		$this->templateField = $templateField;
		$this->content = $content;
	}

	/**
	 * @return TemplateField
	 */
	public function getTemplateField(): TemplateField {
		return $this->templateField;
	}

	/**
	 * Guaranteed to always return a string.
	 *
	 * @return string
	 */
	public function getContent(): string {
		return $this->content ?? $this->templateField->getDefault() ?? "";
	}

	/**
	 * Sets this content field's content
	 *
	 * @param string $content
	 */
	public function setContent(string $content = null) {
		$this->content = $content;
	}

	/**
	 * Returns if content exists within this field.
	 *
	 * @return bool
	 */
	public function hasContent(): bool {
		return !is_null($this->content) && $this->content !== "";
	}

	public function __toString() {
		return $this->getContent();
	}
}

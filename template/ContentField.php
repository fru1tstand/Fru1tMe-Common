<?php
namespace me\fru1t\common\template;

/**
 * ContentFields are the values that fit into TemplateField locations within a template.
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
   * Creates a new ContentField given a TemplateField and optionally, content. Consider using
   * {@link ContentField::of(TemplateField)} for stylistic purposes.
   *
   * @param TemplateField $templateField
   * @param string|null $content (optional)
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
   * Returns the value of this ContentField, or the default value of the TemplateField if no content
   * was given.
	 *
	 * @return string
	 */
	public function getContent(): string {
		return ($this->hasContent()) ? $this->content : $this->templateField->getDefaultValue();
	}

	/**
	 * Sets the value to be retrieved from the ContentField.
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
		return !is_null($this->content) && $this->content != "";
	}

	public function __toString() {
		return $this->getContent();
	}
}

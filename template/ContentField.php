<?php
namespace me\fru1t\common\template;
use me\fru1t\common\language\Preconditions;

/**
 * ContentFields are the values that fit into TemplateField locations within a template.
 */
class ContentField {
	/**
	 * Creates a ContentField given a TemplateField.
	 * @param TemplateField $templateField
	 * @return ContentField
	 */
	public static function of(TemplateField $templateField): ContentField {
		return new ContentField($templateField);
	}

	/** @var TemplateField */
	private $templateField;
	/** @var string */
	private $content;
  /** @var bool */
  private $hasContentBeenSet;

  /**
   * Creates a new ContentField given a TemplateField and optionally, content. Consider using
   * {@link ContentField::of(TemplateField)} for stylistic purposes.
   * @param TemplateField $templateField
   * @param string|null $content (optional)
   */
	public function __construct(TemplateField $templateField, ?string $content = null) {
		$this->templateField = $templateField;
		$this->content = $content;
    $this->hasContentBeenSet = false;
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
	 * @return null|string
	 */
	public function getContent(): ?string {
		return ($this->hasContent()) ? $this->content : $this->templateField->getDefaultValue();
	}

	/**
	 * Sets the value to be retrieved from the ContentField.
	 * @param null|string $content
	 */
	public function setContent(?string $content = null): void {
		$this->content = $content;
    $this->hasContentBeenSet = true;
	}

	/**
	 * Returns if content exists beyond the default value.
	 * @return bool
	 */
	public function hasContent(): bool {
    return $this->hasContentBeenSet;
	}

	public function __toString() {
		return $this->getContent();
	}
}

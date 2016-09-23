<?php
namespace me\fru1t\common\template;

/**
 * TemplateFields are the placeholders for template files. These define where customizable content
 * is allowed to be in a template, and can be defined as either required, optional null, or optional
 * with default content.
 */
class TemplateField {
	/**
	 * Creates a new TemplateFieldBuilder.
	 *
	 * @return TemplateFieldBuilder
	 */
	public static function newBuilder(): TemplateFieldBuilder {
		return new TemplateFieldBuilder();
	}

	/** @type string */
	private $id;
	/** @type bool */
	private $isRequired;
  /** @type string|null */
  private $defaultValue;

  /**
   * Creates a new TemplateField with the given name, requirement, and default value. Consider using
   * {@link TemplateField::newBuilder()} for stylistic purposes.
   *
   * @param string $id The identifier used for this template field.
   * @param bool $isRequired (optional) Defaults to false. Sets whether or not this template field
   *     is required for the template to render or not.
   * @param string|null $defaultValue (optional) Defaults to null. Requires isRequired to be false.
   *     Sets the default value for this field to be used if no value is passed in the content.
   */
	public function __construct(string $id, bool $isRequired = false, string $defaultValue = null) {
		$this->id = $id;
		$this->isRequired = $isRequired;
    $this->defaultValue = $defaultValue;
	}

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return boolean
	 */
	public function isRequired() {
		return $this->isRequired;
	}

  /**
   * @return string
   */
  public function getDefaultValue() {
    return $this->defaultValue ?? "";
  }

	/**
	 * Creates a new content field based on this template field.
	 *
	 * @return ContentField
	 */
	public function newContentField(): ContentField {
		return new ContentField($this);
	}
}

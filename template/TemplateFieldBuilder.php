<?php
namespace me\fru1t\common\template;
use RuntimeException;

/**
 * The builder to {@link TemplateField}.
 */
class TemplateFieldBuilder {
	/** @type string|null */
	private $id;
	/** @type bool */
	private $isRequired;
  /** @type string|null */
  private $defaultValue;

  /**
   * Creates a new, empty instance of TemplateFieldBuilder.
   */
	public function __construct() {
		$this->id = null;
		$this->defaultValue = null;
		$this->isRequired = false;
	}

	/**
	 * Sets the id of this TemplateField which is used to identify it in the template.
	 *
	 * @param string $id
	 * @return TemplateFieldBuilder this
	 */
	public function called(string $id): TemplateFieldBuilder {
		$this->id = $id;
		return $this;
	}

  /**
   * Sets this field as being required which will cause the template engine to throw an error
   * if content for this field is not provided upon rendering.
   *
   * @return TemplateFieldBuilder this
   */
  public function asRequired(): TemplateFieldBuilder {
    $this->isRequired = true;
    return $this;
  }

	/**
	 * Sets the default value to use when a value isn't provided by the Content page.
	 *
	 * @param string $default
	 * @return TemplateFieldBuilder this
	 */
	public function defaultingTo(string $default): TemplateFieldBuilder {
		$this->defaultValue = $default;
		return $this;
	}

	/**
	 * Checks and Builds this TemplateField.
	 *
	 * @return TemplateField
	 */
	public function build(): TemplateField {
	  if ($this->isRequired && $this->defaultValue != null) {
	    throw new RuntimeException("A default value cannot be given to a required template field.");
    }

		return new TemplateField($this->id, $this->isRequired, $this->defaultValue);
	}
}

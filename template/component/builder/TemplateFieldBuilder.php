<?php
namespace common\template\component\builder;
use common\template\component\TemplateField;

/**
 * Class FieldBuilder
 */
class TemplateFieldBuilder {
	/** @type string */
	private $name;
	/** @type string */
	private $default;
	/** @type bool */
	private $required;

	/**
	 * Use {@link TemplateField::newBuilder()}
	 *
	 * @internal
	 */
	public function __construct() {
		$this->name = null;
		$this->default = null;
		$this->required = false;
	}

	/**
	 * Sets the name of this TemplateField
	 *
	 * @param string $name
	 * @return TemplateFieldBuilder
	 */
	public function called(string $name): TemplateFieldBuilder {
		$this->name = $name;
		return $this;
	}

	/**
	 * Sets the default value to use when a ContentField value is not provided.
	 *
	 * @param string $default
	 * @return TemplateFieldBuilder
	 */
	public function defaultingTo(string $default): TemplateFieldBuilder {
		$this->default = $default;
		return $this;
	}

	/**
	 * Sets this field as being required which will cause the template engine to throw an error
	 * if content for this field is not provided upon rendering.
	 *
	 * @return TemplateFieldBuilder
	 */
	public function asRequired(): TemplateFieldBuilder {
		$this->required = true;
		return $this;
	}

	/**
	 * Sets this field as being not required (which is default) but sometimes being explicit is
	 * better.
	 *
	 * @return TemplateFieldBuilder
	 */
	public function asNotRequired(): TemplateFieldBuilder {
		$this->required = false;
		return $this;
	}

	/**
	 * Builds this TemplateField.
	 *
	 * @return TemplateField
	 */
	public function build(): TemplateField {
		return new TemplateField($this->name, $this->required, $this->default);
	}
}

<?php
namespace common\template\component;
use common\template\component\builder\TemplateFieldBuilder;

/**
 * Specifies a field within a template.
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
	private $name;
	/** @type string */
	private $default;
	/** @type bool */
	private $required;

	/**
	 * <p>Internal use only. Use {@link Field::newbuilder}.
	 *
	 * @param string $name
	 * @param string $default
	 * @param bool $required
	 * @internal
	 */
	public function __construct(string $name, string $default = "", bool $required = false) {
		$this->name = $name;
		$this->default = $default;
		$this->required = $required;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getDefault() {
		return $this->default;
	}

	/**
	 * @return boolean
	 */
	public function isRequired() {
		return $this->required;
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

<?php
namespace common\template\component\builder;
use common\template\component\Template;
use common\template\component\TemplateException;
use common\template\component\TemplateField;

/**
 * A builder for Templates.
 *
 * @package common\template\builder
 */
class TemplateBuilder {
	/** @type TemplateField[] */
	private $fields;
	/** @type callable */
	private $getRenderContentsFn;
	/** @type string */
	private $id;

	/**
	 * <p>Use {@link Template::newBuilder}.
	 * <p>Creates a new TemplateBuilder
	 *
	 * @internal
	 */
	public function __construct() {
		$this->fields = [];
		$this->getRenderContentsFn = null;
		$this->id = null;
	}

	/**
	 * Add a field to this template.
	 *
	 * @param TemplateField $field
	 * @return TemplateBuilder
	 */
	public function addField(TemplateField $field): TemplateBuilder {
		if (isset($this->fields[$field->getName()])) {
			throw new TemplateException("$field already exists.");
		}
		$this->fields[$field->getName()] = $field;
		return $this;
	}

	/**
	 * Add a field to this template.
	 *
	 * @param TemplateFieldBuilder $fieldBuilder
	 * @return TemplateBuilder
	 */
	public function addFieldBuilder(TemplateFieldBuilder $fieldBuilder): TemplateBuilder {
		$this->addField($fieldBuilder->build());
		return $this;
	}

	/**
	 * Sets the template id.
	 *
	 * @param string $id
	 * @return TemplateBuilder
	 */
	public function id(string $id): TemplateBuilder {
		$this->id = $id;
		return $this;
	}

	/**
	 * <p>Sets the getRenderContentsFn. This function must accept an array parameter and return the
	 * HTML as a string. The passed array parameter will be an associative array mapping all
	 * template fields to their respective values.
	 *
	 * <p>An example use for this method:
	 * <pre>
	 * 	...
	 * 	setGetRenderContentsFn(function($fields) {
	 * 		return <<<HTML
	 * 			<div>Username: {$fields[self::username]}</div>
	 * 	HTML; // Left aligned due to HEREDOC rules.
	 * 	})...
	 * </pre>
	 *
	 * @param callable $fn
	 * @return TemplateBuilder
	 */
	public function setRenderFn(callable $fn): TemplateBuilder {
		$this->getRenderContentsFn = $fn;
		return $this;
	}

	/**
	 * Creates a new template for this builder and registers it within the pool of available
	 * templates to use for content pages.
	 *
	 * @return Template
	 * @throws TemplateException
	 */
	public function build(): Template {
		if ($this->id === null) {
			throw new TemplateException("Templates must include their template id.");
		}
		if (count($this->fields) == 0) {
			throw new TemplateException("{$this->id} cannot have 0 fields. "
					. "Instead, you may want to look into using the EmptyTemplate.");
		}
		if ($this->getRenderContentsFn === null) {
			throw new TemplateException("{$this->id} must define a getRenderContentsFn.");
		}

		return new Template($this->id, $this->fields, $this->getRenderContentsFn);
	}
}

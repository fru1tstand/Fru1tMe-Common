<?php
namespace common\template\builder;
use common\template\internal\TemplateException;
use common\template\Template;
use common\template\TemplateUtils;

/**
 * A builder for Templates.
 *
 * @package common\template\builder
 */
class TemplateBuilder {
	/** @type string[] */
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
	 * Sets the entire fields column for this template.
	 *
	 * @param array $fields
	 * @return TemplateBuilder
	 */
	public function setFields(array $fields): TemplateBuilder {
		$this->fields = $fields;
		return $this;
	}

	/**
	 * Adds a field for this template.
	 *
	 * @param string $field
	 * @return TemplateBuilder
	 */
	public function addField(string $field): TemplateBuilder {
		$this->fields[] = $field;
		return $this;
	}

	/**
	 * Sets the template id.
	 *
	 * @param string $id
	 * @return TemplateBuilder
	 */
	public function setId(string $id): TemplateBuilder {
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
	public function setGetRenderContentsFn(callable $fn): TemplateBuilder {
		$this->getRenderContentsFn = $fn;
		return $this;
	}

	/**
	 * <p>For the most part, you want to use {@link TemplateBuilder::register} to both build and
	 * register this TemplateBuilder instead of calling this function.
	 * <p>Builds and returns this template.
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

	/**
	 * Builds and registers the result of this TemplateBuilder so that ContentPages can use it.
	 *
	 * @throws TemplateException
	 */
	public function register() {
		TemplateUtils::storeTemplate($this->build());
	}
}

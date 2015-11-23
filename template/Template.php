<?php
namespace common\template;
use common\template\builder\TemplateBuilder;

/**
 * Class Template
 *
 * Defines a structure for content to populate.
 */
class Template {
	/**
	 * Creates a new builder for a Template.
	 *
	 * @return TemplateBuilder
	 */
	public static function newBuilder(): TemplateBuilder {
		return new TemplateBuilder();
	}


	/** @type callable */
	private $getRenderContentsFn;
	/** @type string[] */
	private $fields;
	/** @type string */
	private $id;

	/**
	 * <p>Instead of doing this the hard way. Use {@link Template::newBuilder}.
	 * <p>Creates a new Template. This should be registered with
	 * {@link TemplateUtils::registerTemplate} so that all content pages will have access to it.
	 *
	 * @param string $id
	 * @param string[] $fields
	 * @param callable $getRenderContentsFn
	 * @internal
	 */
	public function __construct(string $id, array $fields, callable $getRenderContentsFn) {
		$this->id = $id;
		$this->fields = $fields;
		$this->getRenderContentsFn = $getRenderContentsFn;
	}

	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * @return string[]
	 */
	public function getFields(): array {
		return $this->fields;
	}

	/**
	 * Returns a completed template given the fields as an associative array.
	 *
	 * @param string[] $fields
	 * @return string
	 */
	public function getRenderContents(array $fields): string {
		// Because PHP thinks $this->getRenderContentsFn() is an object method instead of treating
		// the field like a variable, we need a surrogate variable to call the function.
		$fn = $this->getRenderContentsFn;
		return $fn($fields);
	}

	/**
	 * Renders this template given the fields mapped to their respective values via an associative
	 * array.
	 *
	 * @param array $fields
	 */
	public function render(array $fields) {
		echo $this->getRenderContents($fields);
	}
}

<?php
namespace common\template\component;
use common\template\component\builder\ContentBuilder;
use common\template\component\builder\TemplateBuilder;

/**
 * Class Template
 *
 * Defines a structure for content to populate.
 */
class Template {
	/** @type Template[] Holds all stored templates in an associative array */
	private static $storedTemplates = [];

	/**
	 * Creates a new builder for a Template.
	 *
	 * @return TemplateBuilder
	 */
	public static function newBuilder(): TemplateBuilder {
		return new TemplateBuilder();
	}

	/**
	 * Creates a new ContentBuilder from a given template id.
	 *
	 * @param string $templateId
	 * @return ContentBuilder
	 */
	public static function newContentBuilder(string $templateId): ContentBuilder {
		if (!isset(self::$storedTemplates[$templateId])) {
			throw new TemplateException("$templateId doesn't exist (or hasn't been registered).");
		}
		return new ContentBuilder(self::$storedTemplates[$templateId]);
	}


	/** @type callable */
	private $getRenderContentsFn;
	/** @type TemplateField[] */
	private $fields;
	/** @type string */
	private $id;

	/**
	 * <p>Instead of doing this the hard way. Use {@link Template::newBuilder}.
	 * <p>Creates a new Template. This should be registered with
	 * {@link TemplateUtils::registerTemplate} so that all content pages will have access to it.
	 *
	 * @param string $id
	 * @param TemplateField[] $fields This needs to be an associative array mapping the field name
	 * to the TemplateField object.
	 * @param callable $getRenderContentsFn
	 * @internal
	 */
	public function __construct(string $id, array $fields, callable $getRenderContentsFn) {
		if (isset(self::$storedTemplates[$id])) {
			throw new TemplateException("$id template already exists.");
		}
		self::$storedTemplates[$id] = $this;

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
	 * @return TemplateField[]
	 */
	public function getFields(): array {
		return $this->fields;
	}

	/**
	 * Returns a completed template given the fields as an associative array.
	 *
	 * @param ContentField[] $fields
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

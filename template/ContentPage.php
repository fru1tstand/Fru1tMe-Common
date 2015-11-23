<?php
namespace common\template;
use common\template\builder\ContentPageBuilder;
use common\template\internal\TemplateException;

/**
 * Class ContentPage
 *
 * Defines a content page associated to a template used in the Fru1tMe templating engine.
 */
class ContentPage {
	/**
	 * Creates a new ContentPageBuilder to create content pages.
	 *
	 * @return ContentPageBuilder
	 */
	public static function newBuilder(): ContentPageBuilder {
		return new ContentPageBuilder();
	}


	/** @var string */
	private $templateId;
	/** @var string[] */
	private $fields;

	/**
	 * <p>This is an internal method. Use {@link ContentPage::newBuilder} instead.
	 * <p>Creates a new content page for a template with populated fields.
	 *
	 * @param string $templateId
	 * @param string[] $fields
	 * @throws TemplateException
	 * @internal
	 */
	public function __construct(string $templateId, array $fields) {
		$this->templateId = $templateId;
		$this->fields = $fields;
	}

	/**
	 * @return string[]
	 */
	public function getFields(): array {
		return $this->fields;
	}

	/**
	 * @return string
	 */
	public function getTemplateId(): string {
		return $this->templateId;
	}

	/**
	 * Returns the HTML created by this content page from the controlling template
	 *
	 * @return string
	 */
	public function getRenderContents(): string {
		return TemplateUtils::getTemplate($this->templateId)->getRenderContents($this->fields);
	}

	/**
	 * Renders the HTML created by this content page from the controlling template.
	 */
	public function render() {
		echo $this->getRenderContents();
	}
}

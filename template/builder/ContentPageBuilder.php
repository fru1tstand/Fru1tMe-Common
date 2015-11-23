<?php
namespace common\template\builder;
use common\template\ContentPage;
use common\template\internal\TemplateException;
use common\template\TemplateUtils;

/**
 * Class ContentPageBuilder
 */
class ContentPageBuilder {
	/** @var string[] */
	private $fields;
	/** @var string */
	private $templateId;

	/**
	 * <p>Use {@link ContentPage::newBuilder}.
	 * <p>Creates a new ContentPageBuilder
	 *
	 * @internal
	 */
	public function __construct() {
		$this->fields = [];
		$this->templateId = null;
	}

	/**
	 * Sets the template id that drives this content page.
	 *
	 * @param string $templateId
	 * @return ContentPageBuilder
	 */
	public function of(string $templateId): ContentPageBuilder {
		$this->templateId = $templateId;
		return $this;
	}

	/**
	 * Sets the given field for this content page with content.
	 *
	 * @param string $field The field to populate.
	 * @param string $content The content to save.
	 * @return ContentPageBuilder This for daisy chaining
	 * @throws TemplateException If the field is already set or invalid.
	 */
	public function with(string $field, string $content): ContentPageBuilder {
		if (isset($this->fields[$field])) {
			throw new TemplateException("$field is already set for this content page");
		}

		$this->fields[$field] = $content;
		return $this;
	}

	/**
	 * Usually content pages should be {@link ContentPageBuilder->store}d to be rendered by a
	 * controller. Be certain this is the method you're looking for.
	 *
	 * @return ContentPage
	 */
	public function build(): ContentPage {
		return new ContentPage($this->templateId, $this->fields);
	}

	/**
	 * Builds and stores this ContentPageBuilder to be rendered.
	 */
	public function store() {
		TemplateUtils::storeContentPage($this->build());
	}
}

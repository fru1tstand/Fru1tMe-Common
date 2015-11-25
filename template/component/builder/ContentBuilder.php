<?php
namespace common\template\component\builder;
use common\template\component\Content;
use common\template\component\ContentField;
use common\template\component\Template;
use common\template\component\TemplateException;
use common\template\TemplateUtils;

/**
 * Creates Content pages.
 */
class ContentBuilder {
	/** @var ContentField[] */
	private $fields;
	/** @var Template */
	private $template;

	/**
	 * <p>Use {@link ContentPage::newBuilder}.
	 * <p>Creates a new ContentPageBuilder
	 *
	 * @param Template $template
	 * @internal
	 */
	public function __construct(Template $template) {
		$this->template = $template;
		$this->fields = [];
		foreach ($template->getFields() as $templateField) {
			$this->fields[$templateField->getName()] = $templateField->newContentField();
		}
	}

	/**
	 * Sets the given field for this content page with content.
	 *
	 * @param string $field The field to populate.
	 * @param string $content The content to save.
	 * @return ContentBuilder This for daisy chaining
	 * @throws TemplateException If the field is already set or invalid.
	 */
	public function with(string $field, string $content): ContentBuilder {
		if (!isset($this->fields[$field])) {
			throw new TemplateException("$field doesn't exist in the {$this->template->getId()} template");
		}
		if (isset($this->fields[$field])) {
			throw new TemplateException("$field is already set for this content page");
		}

		$this->fields[$field]->setContent($content);
		return $this;
	}

	/**
	 * Usually content pages should be {@link ContentPageBuilder->store}d to be rendered by a
	 * controller. Be certain this is the method you're looking for.
	 *
	 * @return Content
	 */
	public function build(): Content {
		return new Content($this->template, $this->fields);
	}

	/**
	 * Builds and stores this ContentPageBuilder to be rendered.
	 */
	public function store() {
		TemplateUtils::storeContent($this->build());
	}
}

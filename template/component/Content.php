<?php
namespace common\template\component;

/**
 * Class Content
 *
 * Defines a content page associated to a template used in the Fru1tMe templating engine.
 */
class Content {
	/** @var Template */
	private $template;
	/** @var ContentField[] */
	private $fields;

	/**
	 * <p>This is an internal method. Use {@link ContentPage::newBuilder} instead.
	 * <p>Creates a new content page for a template with populated fields.
	 *
	 * @param Template $template
	 * @param ContentField[] $fields This needs to be an associative array mapping the field name
	 * to the ContentField object.
	 * @throws TemplateException
	 * @internal
	 */
	public function __construct(Template $template, array $fields) {
		foreach ($fields as $field) {
			if ($field->getTemplateField()->isRequired() && !$field->hasContent()) {
				throw new TemplateException(
						$field->getTemplateField()->getName()
						. "is required but is not set in this content page");
			}
		}
		$this->template = $template;
		$this->fields = $fields;
	}

	/**
	 * Returns the HTML created by this content page from the controlling template
	 *
	 * @return string
	 */
	public function getRenderContents(): string {
		return $this->template->getRenderContents($this->fields);
	}

	/**
	 * Renders the HTML created by this content page from the controlling template.
	 */
	public function render() {
		echo $this->getRenderContents();
	}
}

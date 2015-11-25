<?php
namespace common\template;
use common\template\component\ContentField;
use common\template\component\TemplateException;
use common\template\component\ContentInterface;
use common\template\component\TemplateField;

/**
 * Provides methods for creating content pages from templates.
 */
abstract class Content implements ContentInterface {
	/** @var TemplateField[] */
	private static $templateFields = null;

	/**
	 * Creates and returns a new empty content page. One should use this method for creating static
	 * content pages.
	 *
	 * @return Content
	 * @throws TemplateException Thrown if you're using this class incorrectly. Doubt you'll see
	 * it. But sometimes it's late or something.
	 */
	public static final function createEmpty(): Content {
		if (static::class == self::class) {
			throw new TemplateException("You're trying to create a new content page without
			specifying the template to use. Try calling #create* through the template (eg.
			TemplateName::create*()) where TemplateName is the class that extends Content.");
		}

		// lol wut? Hooray for late static binding!
		return new static();
	}

	/**
	 * Creates and returns a new content page with the given content.
	 *
	 * @param array $contents An associative array mapping field names for this template to the
	 * content value.
	 * @return Content
	 * @throws TemplateException
	 */
	public static final function createFromFields(array $contents): Content {
		$contentPage = self::createEmpty();
		foreach ($contents as $field => $content) {
			$contentPage->with($field, $content);
		}
		return $contentPage;
	}

	/**
	 * Returns the TemplateField objects associated to this content page. These are the fields
	 * that are used within the template rendering method.
	 *
	 * @return TemplateField[]
	 */
	public static final function getTemplateFields(): array {
		if (static::class == self::class) {
			throw new TemplateException("You're trying to get template fields without specifying
			the template you want from. Try calling #getTemplateFields through the template (eg.
			TemplateName::getTemplateFields()) where TemplateName is the class that extends
			Content.");
		}
		if (self::$templateFields === null) {
			self::$templateFields = static::getTemplateFields_Internal();
		}
		return self::$templateFields;
	}


	/** @type ContentField[] */
	private $contentFields;

	/**
	 * Creates a new content page
	 */
	protected final function __construct() {
		$this->contentFields = [];
		foreach (self::getTemplateFields() as $templateField) {
			$this->contentFields[$templateField->getName()] = $templateField->newContentField();
		}
	}

	/**
	 * Specifies content for a given field for this content page.
	 *
	 * @param string $field
	 * @param string $content
	 * @return Content
	 * @throws TemplateException
	 */
	public final function with(string $field, string $content): Content {
		if (!isset($this->contentFields[$field])) {
			throw new TemplateException("$field doesn't exist in this template");
		}
		if (isset($this->contentFields[$field])) {
			throw new TemplateException("$field is already set for this content page");
		}

		$this->contentFields[$field]->setContent($content);
		return $this;
	}

	/**
	 * Returns the template HTML populated with this content page.
	 *
	 * @return string
	 * @throws TemplateException
	 */
	public final function getRenderContents(): string {
		foreach ($this->contentFields as $contentField) {
			if ($contentField->getTemplateField()->isRequired() && !$contentField->hasContent()) {
				throw new TemplateException(
						$contentField->getTemplateField()->getName()
						. " is required for this template.");
			}
		}
		return static::getRenderContent($this->contentFields);
	}

	/**
	 * Renders the HTML created by this content page from the controlling template.
	 */
	public final function render() {
		echo $this->getRenderContents();
	}
}

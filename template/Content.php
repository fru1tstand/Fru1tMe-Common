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
	/** Renders the template and content as plain text HTML (default) */
	const RENDER_FORMAT_ALL_HTML = 1;
	/** Renders solely the content field as JSON */
	const RENDER_FORMAT_CONTENT_ONLY_JSON = 2;

	/** The name for the key in the json output array that specifies the template name */
	const JSON_TEMPLATE_KEY = "template";

	/** @var TemplateField[][] */
	private static $templateFields = null;

	/** @type int Holds the render options for content rendering */
	private static $renderFormat = self::RENDER_FORMAT_ALL_HTML;

	/**
	 * Creates and returns a new empty content page. One should use this method for creating static
	 * content pages.
	 *
	 * @return Content
	 * @throws TemplateException Thrown if you're using this class incorrectly. Doubt you'll see
	 * it. But sometimes it's late or something.
	 */
	public static final function createContent(): Content {
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
	public static final function createContentFrom(array $contents): Content {
		$contentPage = self::createContent();
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
		if (!isset(self::$templateFields[static::class])) {
			self::$templateFields[static::class] = static::getTemplateFields_Internal();
		}
		return self::$templateFields[static::class];
	}

	/**
	 * Sets the render format for all content pages. This will effect all subsequent renderings so
	 * setting this multiple times may be necessary. Use the RENDER_FORMAT constants defined in
	 * this class. An invalid value will default to RENDER_FORMAT_ALL_HTML.
	 *
	 * @param int $renderFormat
	 */
	public static final function setRenderFormat(int $renderFormat) {
		self::$renderFormat = $renderFormat;
	}


	/** @type ContentField[] */
	private $contentFields;

	/**
	 * Instantiates this content page.
	 */
	private final function __construct() {
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
	public final function with(string $field, string $content = null): Content {
		if (!isset($this->contentFields[$field])) {
			throw new TemplateException("$field doesn't exist in " . static::class);
		}
		if ($this->contentFields[$field]->hasContent()) {
			throw new TemplateException("$field is already set for " . static::class);
		}

		$this->contentFields[$field]->setContent($content);
		return $this;
	}

	/**
	 * Returns the template HTML populated with this content page. The output of this method is
	 * dependent on the renderFormat flag which can change if this outputs the entire template
	 * or solely the content.
	 *
	 * @param bool $forceRenderAll Disables global flag dependency and always renders both
	 * @return string
	 * @throws TemplateException
	 */
	public final function getRenderContents(bool $forceRenderAll = false): string {
		foreach ($this->contentFields as $contentField) {
			if ($contentField->getTemplateField()->isRequired() && !$contentField->hasContent()) {
				throw new TemplateException(
						$contentField->getTemplateField()->getName()
						. " is required for this template.");
			}
		}

		if (!$forceRenderAll && self::$renderFormat == self::RENDER_FORMAT_CONTENT_ONLY_JSON) {
			$jsonArray = [];
			foreach ($this->contentFields as $key => $field) {
				$jsonArray[$key] = $field->getContent();
			}
			$jsonArray[self::JSON_TEMPLATE_KEY] = static::class;
			return json_encode($jsonArray);
		}

		return static::getTemplateRenderContents($this->contentFields);
	}

	/**
	 * Renders the HTML created by this content page from the controlling template. The output of
	 * this method is dependent on the renderFormat flag which can change if this outputs the
	 * entire template or solely the content.
	 *
	 * @param bool $forceRenderAll Disables global flag dependency and always renders both
	 * template and content.
	 * @throws TemplateException
	 */
	public final function render(bool $forceRenderAll = false) {
		echo $this->getRenderContents($forceRenderAll);
	}
}

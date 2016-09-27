<?php
namespace me\fru1t\common\template;
use me\fru1t\common\language\Http;
use me\fru1t\common\mysql\QueryResult;
use RuntimeException;

/**
 * Provides the base methods for creating and displaying a template with associated content.
 * Contains control structures to render as JSON-only.
 */
abstract class Template implements TemplateInterface {
	/** The name for the key in the json output array that specifies the template name */
	const JSON_TEMPLATE_KEY = "template";

	/** @var TemplateField[][] */
	private static $templateFields = null;

	/**
	 * Creates a new template page to produce content with.
	 *
	 * @return Template
	 */
	public static final function start(): Template {
		if (static::class == self::class) {
      throw new RuntimeException("You're trying to create a new content page without"
          . " specifying the template to use. Try calling #create through the template (eg."
          . " TemplateName::create()) where TemplateName is the class that extends Template.");
    }

		// Returns a new instance of the class used to call this method.
		return new static();
	}

  /**
   * Creates a new template page given a map of content, keyed by TemplateField IDs, with values
   * of the content wanting to be rendered.
   *
   * @param array $contents
   * @return Template
   */
	public static final function startFrom(array $contents): Template {
		$contentPage = self::start();
		foreach ($contents as $field => $content) {
			$contentPage->with($field, $content);
		}
		return $contentPage;
	}

  /**
   * Creates as many templates as there are results from the query result.
   *
   * @param QueryResult $queryResult
   * @return Template[]
   */
	public static final function createFromQueryResult(QueryResult $queryResult): array {
	  if (static::class == self::class) {
      throw new RuntimeException("You're trying to make content without saying which template"
          . " you want to use Try calling #createFromQueryResult through the template (eg."
          . " TemplateName::createFromQueryResult()) where TemplateName is the class that extends"
          . " Template.");
    }
	  $results = [];
	  $queryResult->forEachResult(function($row) use ($results) {
	    $results[] = static::startFrom($row);
    });
    return $results;
  }

	/**
	 * Returns the TemplateField objects associated to this content page. These are the fields
	 * that are used within the template rendering method.
	 *
	 * @return TemplateField[]
	 */
	public static final function getTemplateFields(): array {
		if (static::class == self::class) {
      throw new RuntimeException("You're trying to get template fields without specifying"
          . " the template you want from. Try calling #getTemplateFields through the template (eg."
          . " TemplateName::getTemplateFields()) where TemplateName is the class that extends"
          . " Template.");
    }
		if (!isset(self::$templateFields[static::class])) {
			self::$templateFields[static::class] = static::getTemplateFields_internal();
		}
		return self::$templateFields[static::class];
	}


	/** @type ContentField[] */
	private $contentFields;

	/**
	 * Instantiates this content page.
	 */
	private final function __construct() {
		$this->contentFields = [];
		foreach (self::getTemplateFields() as $templateField) {
			$this->contentFields[$templateField->getId()] = $templateField->newContentField();
		}
	}

	/**
	 * Specifies content for a given field for this content page.
	 *
	 * @param string $fieldId
	 * @param string $content (optional) Defaults to null.
	 * @return Template
	 */
	public final function with(string $fieldId, string $content = null): Template {
		if (!isset($this->contentFields[$fieldId])) {
			throw new RuntimeException("$fieldId doesn't exist in " . static::class);
		}
		if ($this->contentFields[$fieldId]->hasContent()) {
			throw new RuntimeException("$fieldId is already set for " . static::class);
		}

		$this->contentFields[$fieldId]->setContent($content);
		return $this;
	}

  /**
   * Creates the HTML with the given content.
   *
   * @param bool $outputNow (optional) Defaults to true. Sets whether or not calling this method
   *     will print the contents out onto the page at the immediate moment it's called.
   * @param bool $forceHtml (optional) Defaults to false. Forces the renderer to output HTML,
   *     regardless of global options. This is used primarily for template nesting.
   * @return string
   */
	public final function render(bool $outputNow = true, bool $forceHtml = false) {
    foreach ($this->contentFields as $contentField) {
      if ($contentField->getTemplateField()->isRequired() && !$contentField->hasContent()) {
        throw new RuntimeException(
            $contentField->getTemplateField()->getId() . " is required for this template.");
      }
    }

    $promisedRenderFormat = $forceHtml ? Templates::FORMAT_HTML : Templates::getRenderFormat();
    $output = null;
    switch($promisedRenderFormat) {
      // Output JSON
      case Templates::FORMAT_JSON:
        $jsonArray = [];
        foreach ($this->contentFields as $key => $field) {
          $jsonArray[$key] = $field->getContent();
        }
        $jsonArray[self::JSON_TEMPLATE_KEY] = static::class;
        $output = json_encode($jsonArray);
        header(Http::HEADER_CONTENT_TYPE_JSON);
        break;

      // Output HTML
      case Templates::FORMAT_HTML:
      default:
        $output = static::getTemplateRenderContents_internal($this->contentFields);
        break;
    }

    if ($outputNow) {
      echo $output;
    }
    return $output;
	}
}

<?php
namespace common\template;
require_once $_SERVER['DOCUMENT_ROOT'] . '/.site/php/fru1tme/Setup.php';

/**
 * Class ContentPage
 */
class ContentPage {
	/** @var $registeredContentPage ContentPage */
	private static $registeredContentPage;

	/**
	 * Sets up the content page to display using TemplateUtils
	 *
	 * @param ContentPage $contentPage
	 */
	public static function register(ContentPage $contentPage) {
		self::$registeredContentPage = $contentPage;
	}

	/**
	 * Executes (renders) the content page that has been registered
	 *
	 * @return boolean Returns true if the page could be rendered.
	 */
	public static function execute() {
		if (!!self::$registeredContentPage) {
			self::$registeredContentPage->render();
			return true;
		}
		return false;
	}

	/** @var $template string */
	private $template;
	/** @var $fields string[] */
	private $fields;

	/**
	 * Creates a new content page for a template with populated fields.
	 *
	 * @param string $templateClass
	 * @param string[] $fields
	 * @throws TemplateException
	 */
	public function __construct($templateClass, $fields) {
		// Check incoming class is of type template
		if (!class_exists($templateClass)) {
			throw new TemplateException("$templateClass isn't a valid class.");
		}
		if (!is_subclass_of($templateClass, 'common\template\TemplateInterface')) {
			throw new TemplateException(
					"$templateClass does not extend the Template abstract class.");
		}

		$templateFields = call_user_func($templateClass . '::getFields');
		// Check for fields
		foreach ($templateFields as $field) {
			if (!isset($fields[$field])) {
				throw new TemplateException(
						get_class($this) . " doesn't define the template field $field");
			}
		}

		if (count($templateFields) !== count($fields)) {
			throw new TemplateException(
					get_class($this) . " has more fields than is declared by  $templateClass");
		}

		$this->template = $templateClass;
		$this->fields = $fields;
	}

	/**
	 * Returns all fields within this content page.
	 *
	 * @return \string[]
	 */
	public function getFields() {
		return $this->fields;
	}

	/**
	 * Fetches this content page's driving template.
	 *
	 * @return string
	 */
	public function getTemplate() {
		return $this->template;
	}

	/**
	 * Renders this content page using its template.
	 */
	public function render() {
		echo call_user_func($this->template . '::getRenderContents', $this->fields);
	}
}

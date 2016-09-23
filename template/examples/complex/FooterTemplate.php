<?php
namespace me\fru1t\common\template\examples\complex;
use me\fru1t\common\template\Template;
use me\fru1t\common\template\TemplateField;

/**
 * Defines the global footer used throughout this example site. This is used in all pages including
 * the front page.
 */
class FooterTemplate extends Template {
  /**
   * @param string[] $fields
   * @return string
   */
  public static function getTemplateRenderContents_internal(array $fields): string {
    return <<<HTML
<footer><!-- Complicated footer --></footer>
HTML;
  }

  /**
   * @return TemplateField[]
   */
  static function getTemplateFields_internal(): array {
    return [];
  }
}

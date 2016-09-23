<?php
namespace me\fru1t\common\template\examples\nested;
use me\fru1t\common\template\Template;
use me\fru1t\common\template\TemplateField;

/**
 * Defines a table template that requires some TDs.
 */
class TableTemplate extends Template {
  const FIELD_TR_CONTENT = "tr-content";

  /**
   * @param string[] $fields
   * @return string
   */
  public static function getTemplateRenderContents_internal(array $fields): string {
    // Templates can be nested within other templates directly. Direct calls are usually only ever
    // non-repeated templates. See NestedContent.php for an example of repeated template generation.
    $footer = TableFooter::start()->render();

    return <<<HTML
<table>
    {$fields[self::FIELD_TR_CONTENT]}
    {$footer}
</table>
HTML;
  }

  /**
   * @return TemplateField[]
   */
  static function getTemplateFields_internal(): array {
    return [
        TemplateField::newBuilder()->called(self::FIELD_TR_CONTENT)->asRequired()->build()
    ];
  }
}

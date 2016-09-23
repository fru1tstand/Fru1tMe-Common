<?php
namespace me\fru1t\common\template\examples\nested;
use me\fru1t\common\template\Template;
use me\fru1t\common\template\TemplateField;

/**
 * Produces <td>s.
 */
class TrTemplate extends Template {
  const FIELD_CONTENT = "content";

  /**
   * @param string[] $fields
   * @return string
   */
  public static function getTemplateRenderContents_internal(array $fields): string {
    return "<tr><td>{$fields[self::FIELD_CONTENT]}</td></tr>";
  }

  /**
   * @return TemplateField[]
   */
  static function getTemplateFields_internal(): array {
    return [
        TemplateField::newBuilder()->called(self::FIELD_CONTENT)->asRequired()->build()
    ];
  }
}

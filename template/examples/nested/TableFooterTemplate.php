<?php
namespace me\fru1t\common\template\examples\nested;
use me\fru1t\common\template\Template;
use me\fru1t\common\template\TemplateField;

/**
 * Defines a re-usable table footer.
 */
class TableFooter extends Template {
  /**
   * @param string[] $fields
   * @return string
   */
  public static function getTemplateRenderContents_internal(array $fields): string {
    return <<<HTML
<tfoot>
  <tr><td>This is a table footer!</td></tr>
</tfoot>
HTML;

  }

  /**
   * @return TemplateField[]
   */
  static function getTemplateFields_internal(): array {
    // Just a simple, re-usable HTML fragment, so there are no fields.
    return [];
  }
}

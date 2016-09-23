<?php
namespace me\fru1t\common\template\examples\complex;
use me\fru1t\common\template\Template;
use me\fru1t\common\template\TemplateField;

/**
 * Defines a result template that houses how a result should look.
 */
class ResultTemplate extends Template {
  const FIELD_RESULT_NAME = "result-name";
  const FIELD_RESULT_URL = "result-url";
  const FIELD_RESULT_DESC = "result-desc";
  const FIELD_RESULT_LIKES = "result-likes";

  const DEFAULT_MIN_LIKES = 10;
  const MIN_LIKES_PARAM = "likes";

  const PAGE_TITLE = "Results";

  /**
   * @param string[] $fields
   * @return string
   */
  public static function getTemplateRenderContents_internal(array $fields): string {
    return <<<HTML
<a class="result" href="{$fields[self::FIELD_RESULT_URL]}">
  <h4>{$fields[self::FIELD_RESULT_NAME]}</h4>
  <p>{$fields[self::FIELD_RESULT_DESC]}</p>
  <div class="result-likes">{$fields[self::FIELD_RESULT_LIKES]}</div>
</a>
HTML;
  }

  /**
   * @return TemplateField[]
   */
  static function getTemplateFields_internal(): array {
    return [
        TemplateField::newBuilder()->called(self::FIELD_RESULT_NAME)->asRequired(),
        TemplateField::newBuilder()->called(self::FIELD_RESULT_URL)->asRequired(),
        TemplateField::newBuilder()->called(self::FIELD_RESULT_DESC)->asRequired(),
        TemplateField::newBuilder()->called(self::FIELD_RESULT_LIKES)->asRequired()
    ];
  }
}

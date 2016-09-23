<?php
namespace me\fru1t\common\template\examples\complex;
use me\fru1t\common\template\Template;
use me\fru1t\common\template\TemplateField;

/**
 * Defines a standard page for an example website. Depends on EmptyPageTemplate and adds a nice nav,
 * a footer, and a stylized content box.
 */
class StandardPageTemplate extends Template {
  const FIELD_HEAD_TITLE = "head-title";
  const FIELD_PAGE_TITLE = "page-title";
  const FIELD_PAGE_CONTENT = "page-content";

  /**
   * @param string[] $fields
   * @return string
   */
  public static function getTemplateRenderContents_internal(array $fields): string {
    $footer = FooterTemplate::start()->render(false, true);
    $body = <<<HTML
<nav><!-- Some complicated nav or sidebar --></nav>
<h1 class="global-header">{$fields[self::FIELD_PAGE_TITLE]}</h1>
<div class="content">{$fields[self::FIELD_PAGE_CONTENT]}</div>
{$footer}
HTML;

    return EmptyPageTemplate::start()
        ->with(EmptyPageTemplate::FIELD_HEAD_TITLE, $fields[self::FIELD_HEAD_TITLE])
        ->with(EmptyPageTemplate::FIELD_BODY, $body)
        ->render(false, true);
  }

  /**
   * @return TemplateField[]
   */
  static function getTemplateFields_internal(): array {
    return [
        TemplateField::newBuilder()->called(self::FIELD_HEAD_TITLE)->asRequired()->build(),
        TemplateField::newBuilder()->called(self::FIELD_PAGE_TITLE)->asRequired()->build(),
        TemplateField::newBuilder()->called(self::FIELD_PAGE_CONTENT)->asRequired()->build()
    ];
  }
}

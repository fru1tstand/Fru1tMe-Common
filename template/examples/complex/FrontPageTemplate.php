<?php
namespace me\fru1t\common\template\examples\complex;
use me\fru1t\common\language\Session;
use me\fru1t\common\template\Template;
use me\fru1t\common\template\TemplateField;

/**
 * Defines the front page. This template could have also simply been a content page using
 * EmptyPageTemplate as it's only used once -- that is, the front page.
 */
class FrontPageTemplate extends Template {
  const FRONT_PAGE_HEAD_TITLE = "The front page";

  /**
   * @param string[] $fields
   * @return string
   */
  public static function getTemplateRenderContents_internal(array $fields): string {
    $loggedInUser = Session::get("logged-in-user") ?? "Visitor";
    $footer = FooterTemplate::start()->render(false, true);
    $body = <<<HTML
<!-- Some awesome front page HTML -->
<div class="thing-that-styles-the-username">{$loggedInUser}</div>
<!-- More awesome front page HTML -->
{$footer}
HTML;
    return EmptyPageTemplate::start()
        ->with(EmptyPageTemplate::FIELD_HEAD_TITLE, self::FRONT_PAGE_HEAD_TITLE)
        ->with(EmptyPageTemplate::FIELD_BODY, $body)
        ->render(false, true);
  }

  /**
   * Provides the fields this template contains.
   *
   * @return TemplateField[]
   */
  static function getTemplateFields_internal(): array {
    return [];
  }
}

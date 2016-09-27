<?php
namespace me\fru1t\common\template\examples\complex;
use me\fru1t\common\template\Template;
use me\fru1t\common\template\TemplateField;

/**
 * Defines an empty HTML page.
 */
class EmptyPageTemplate extends Template {
  const FIELD_HEAD_TITLE = "head-title";
  const FIELD_BODY = "body";

  /**
   * Produces the content this template defines in the form of an HTML string. This method is passed
   * a map with template field names as keys, and values that the content page provides.
   *
   * @param string[] $fields An associative array mapping fields to ContentField objects.
   * @return string
   */
  public static function getTemplateRenderContents_internal(array $fields): string {
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
	<title>My Website - {$fields[self::FIELD_HEAD_TITLE]}</title>
	<meta charset="UTF-8" />
	
	<link href="some-host.cdn/library.css" rel="stylesheet" />
</head>

<body>
  {$fields[self::FIELD_BODY]}
  
  <script src="some-other-host.cdn/library.js"></script>
</html>
HTML;

  }

  /**
   * Provides the fields this template contains.
   *
   * @return TemplateField[]
   */
  static function getTemplateFields_internal(): array {
    return [
        TemplateField::newBuilder()->called(self::FIELD_HEAD_TITLE)->asRequired()->build(),
        TemplateField::newBuilder()->called(self::FIELD_BODY)->asRequired()->build()
    ];
  }
}

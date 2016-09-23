<?php
namespace me\fru1t\common\template\examples\basic;
use me\fru1t\common\template\Template;
use me\fru1t\common\template\TemplateField;

/**
 * Defines a simple template.
 */
class SimpleTemplate extends Template {
	// You may specify template fields anywhere (or nowhere at all, and just use magic strings),
	// but I find it's easiest just to define them within the template definition itself. These
	// fields are what the template will use when defining the render function.
	const FIELD_CONTENT = "content";

	// We must now define a method that returns the template HTML given the fields in an
	// associative array. In this SimpleTemplate example, we define a simple HTML web page with the
  // content as the body text.
	/**
	 * @param string[] $fields
	 * @return string
	 */
	public static function getTemplateRenderContents_internal(array $fields): string {
		return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
	<title>My Fru1tMe Powered Website</title>
	<meta charset="UTF-8" />
</head>

<body>
	{$fields[SimpleTemplate::FIELD_CONTENT]}
</body>
</html>
HTML;
	}

	// This method should return all fields associated to this template in the form of an array.
	// Again, in our case, we require a single field called "content".
	/**
	 * @return TemplateField[]
	 * @internal
	 */
	static function getTemplateFields_internal(): array {
		return [
			TemplateField::newBuilder()
					->called(self::FIELD_CONTENT)
					->asRequired()->build()
		];
	}
}




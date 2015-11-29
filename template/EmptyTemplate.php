<?php
namespace common\template;
use common\template\component\ContentField;
use common\template\component\TemplateField;

/**
 * This template provides the ability to specify content-only pages with no wrapping text.
 */
// Each template must extend TemplateIdentifier
class EmptyTemplate extends Content {
	// You may specify template fields anywhere (or nowhere at all, and just use magic strings),
	// but I find it's easiest just to define them within the template definition itself. These
	// fields are what the template will use when defining the render function.
	const FIELD_CONTENT = "content";


	// We must now define a method that returns the template HTML given the fields in an
	// associative array. In this EmptyTemplate example, we do nothing more than passing through
	// the content that is given to us, so it simply returns the field. On more complex templates
	// (like ones that produce entire page layouts), I suggest using heredocced strings. An
	// example of this is available at the end of this template.
	/**
	 * @param ContentField[] $fields
	 * @return string
	 */
	public static function getTemplateRenderContents(array $fields): string {
		return $fields[self::FIELD_CONTENT]->getContent();
	}

	// This method should return all fields associated to this template in the form of an array.
	// Again, in our case, we require a single field called "content".
	/**
	 * @return TemplateField[]
	 * @internal
	 */
	static function getTemplateFields_Internal(): array {
		return [
			TemplateField::newBuilder()
					->called(self::FIELD_CONTENT)
					->asRequired()->build()
		];
	}

	// Here is an example of heredoccing with some extra HTML. This is NOT part of this
	// EmptyTemplate class and is just here as an example. I am, however, using the same fields
	// from the EmptyTemplate class.
	public static function getTemplateRenderContentExampleWithHeredoc(array $fields): string {
		return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
	<title>My FmTemplate Powered Website</title>
	<meta charset="UTF-8" />
</head>

<body>
	<nav>
		<ul>
			<li><a href="/">Home</a></li>
		</ul>
	</nav>

	<div>{$fields[EmptyTemplate::FIELD_CONTENT]}</div>

	<footer>
		Created, with love, by Kodlee Yin.
	</footer>
</body>
</html>
HTML;
	}
}




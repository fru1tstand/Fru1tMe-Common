<?php
namespace common\template;
use common\template\component\Template;
use common\template\component\TemplateIdentifier;

	/**
 * This template provides the ability to specify content-only pages with no wrapping text.
 */
// Each template must extend TemplateIdentifier
class EmptyTemplate extends TemplateIdentifier {
	// You may specify template fields anywhere (or nowhere at all, and just use magic strings),
	// but I find it's easiest just to define them within the template definition itself. These
	// fields are what the template will use when defining the render function.
	const FIELD_CONTENT = "content";
}

// We must now define a closure method to return the template's HTML. This simply a function that
// is passed an associative array with the template's fields populated with content. In this
// example, EmptyTemplate provides no other HTML so I simply return the only field we defined.
// Other templates may be more complex and require larger blocks of text. In this situation, I
// suggest using heredoccing to leverage the use of inline. An example of this is available at the
// end of this file.
// I decided to define this outside of EmptyTemplate because (unfortunately) you can't define
// closures as constants (yet) in PHP. So for ease of definition, I just stuck it outside the class.
// Alternatively, one could define it inside the template class to leverage the "self" keyword to
// shorten writing fields.
$renderFn = function(array $fields): string {
	return $fields[EmptyTemplate::FIELD_CONTENT];
};

// Now we actually make the template via the TemplateBuilder
Template::newBuilder()
	// We use that magical #getId() method that is defined in TemplateIdentifier (it's really
	// just the fully qualified class name)
	->id(EmptyTemplate::getId())

	// Add the field(s) associated to this template. Again in our case, it's just the field we
	// called "content".
	->addField(EmptyTemplate::FIELD_CONTENT)

	// Set the render function
	->setRenderFn($renderFn)

	// Finally, register the template, and that's it!
	->register();


// Here is an example of heredoccing with some extra HTML. This is NOT part of this EmptyTemplate
// class and is just here as a more complex example. I am, however, using the same fields from
// the EmptyTemplate class.
$renderFn = function(array $fields): string {
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
};

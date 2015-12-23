## `common\template`
Super simple (fast and lightweight) PHP templating.

#### Why this?
+ No {{handlebar}} string replacements means FAST non-cached content.
+ User control over rendering allows reusable template fragments (templates within templates within templates).
+ MySQL integration enables loading templates directly from the database.
+ JSON output for AJAX supported websites.
+ Strong template definition enables content guarantees.

#### Setup
The project structure should be set up in such a way that the Content pages are what are being navigated to. I like to use a `.htaccess` file that looks like
```
RewriteEngine On
RewriteRule ^\.site/.+ - [L,NC]
RewriteRule ^([^?.]+)(\?.+)?$ /index.php?page=$1 [L,QSA]
```

With an index.php file at the root directory `/index.php` that looks like
```php
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/.site/php/<project>/Setup.php';
use common\template\TemplateUtils;

TemplateUtils::renderContentFromUrl(
		$_SERVER['DOCUMENT_ROOT'] . "/.site/php/<project>/html/content",
		"/index.php");

```

This way, a request to `domain.tld/foo/bar` will keep the pretty URL, but be translated into `domain.tld/index.php?page=foo/bar`.
The templating engine will then look inside `/.site/php/<project>/html/content` for to find `foo/bar.php` or `foo/bar/index.php`

#### Examples
###### Static website that has multiple pages (re-uses header, footer, etc)
```php
// /.site/php/<project>/html/template/StaticPage.php (the template)
<?php
namespace <project>\html\template;
require_once $_SERVER['DOCUMENT_ROOT'] . '/.site/php/<project>/Setup.php';
use common\template\component\ContentField;
use common\template\component\TemplateField;
use common\template\Content;

class StaticPage extends Content {
	const FIELD_BODY = "body";
	const FIELD_TITLE = "title";

	public static function getTemplateRenderContents(array $fields): string {
		return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
	<title>{$fields[self::FIELD_TITLE]}</title>
	<meta charset="UTF-8" />
</head>
<body>
	<nav>...</nav>
	<div id="global-content">{$fields[self::FIELD_BODY]}</div>
	<footer>...</footer>
</body>
</html>
HTML;
	}

	public static function getTemplateFields_Internal(): array {
		return [
				TemplateField::newBuilder()->called(self::FIELD_BODY)->asRequired()->build(),
				TemplateField::newBuilder()->called(self::FIELD_TITLE)->asRequired()->build()
		];
	}
}
```

```php
// /.site/php/<project>/html/content/index.php (content page)
<?php
namespace <project>\html\content;
require_once $_SERVER['DOCUMENT_ROOT'] . '/.site/php/<project>/Setup.php';
use <project>\html\template\StaticPage;

$body = <<<HTML
<h1>Hi welcome to <project></h1>
HTML;

StaticPage::createContent()
	->with(StaticPage::FIELD_TITLE, "Home")
	->with(StaticPage::FIELD_BODY, $body)
	->render();
```

###### Loading content from a database
```php
// /.site/php/<project>/html/template/Card.php (template)
<?php
namespace <project>\html\template;
require_once $_SERVER['DOCUMENT_ROOT'] . '/.site/php/<project>/Setup.php';
use common\template\component\ContentField;
use common\template\component\TemplateField;
use common\template\Content;

class Card extends Content {
	const FIELD_TITLE = "title";
	const DEFAULT_TITLE = "[No Title]";

	public static function getTemplateRenderContents(array $fields): string {
		$time = round((time() - $fields[self::FIELD_POST_DATE]->getContent()) / 60) - 1;
		return <<<HTML
<div>
	<div class="title">{$fields[self::FIELD_TITLE]}</div>
</div>
HTML;
	}

	static function getTemplateFields_Internal(): array {
		return [
			TemplateField::newBuilder()->called(self::FIELD_TITLE)->asNotRequired()->defaultingTo(self::DEFAULT_TITLE)->build()
		];
	}
}
```

```php
// /.site/php/<project>/html/content/index.php (content page)
<?php
namespace <project>\html\content;
require_once $_SERVER['DOCUMENT_ROOT'] . '/.site/php/<project>/Setup.php';
use <project>\html\template\StaticPage;
use <project>\html\template\Card;

$cards = Card::createContentsFromQuery("SELECT title AS " . Card::FIELD_TITLE . " FROM card");
$cardHtml = "";
foreach ($cards as $card) {
	$cardHtml .= $card->getRenderContents();
}

$body = <<<HTML
<h1>Hi welcome to <project></h1>
<div>$cardHtml</div>
HTML;

StaticPage::createContent()
	->with(StaticPage::FIELD_TITLE, "Home")
	->with(StaticPage::FIELD_BODY, $body)
	->render();
```

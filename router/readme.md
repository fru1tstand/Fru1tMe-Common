## `me\fru1t\common\router`
Centralized PHP routing to protect files and create setup guarantees.

#### Why use this?
+ Eliminates the need for state checking (eg. session starting, database connection handling, etc).
+ URL Routing with pretty URLs.
+ Protects against arbitrary loading of PHP files.

#### Setup
Entry point: `Router::setup()`.
+ `#setContentDirectory(string)` - Sets the directory from which the router will use when looking
  for files to open. See "How it works" for more detail.
+ `#setDefaultContentPagePath(string)` - Sets the content page that should appear if no page is
  specified (much like the index page).
+ `#setErrorPagePath(string)` - Sets the page that should appear if an HTTP errors occurs (eg. 404)
+ `#setPageParameterName` - Sets the name of the parameter the router uses to look for page
  requests.
+ `#map(Route)` - Defines an exceptions to content routing by specifying a single address to route
  to a single file. See `Route` for more details.
+ `#complete()` - Completes setup and triggers static file routing. It's wise to place this setup
  as the first module so that static file requests need not wait for other modules to load.

For example, a valid set of instructions would be:
```
Router::setup()
    ->setContentDirectory('../php/me/fru1t/example/content')
    ->setDefaultContentPagePath('index.php')
    ->setErrorPagePath('error.php')
    ->setPageParameterName(Router::DEFAULT_PAGE_PARAMETER_NAME)
    ->map(Route::newBuilder()
        ->whenRequested('styles.css')
        ->provide('../styles/global.css')
        ->withHeader(Http::HEADER_CONTENT_TYPE_CSS)
        ->build())
    ->map(Route::newBuilder()
        ->whenRequested('global.css.map')
        ->provide('../styles/global.css.map')
        ->build())
    ->complete();
```

Implementation note: Content pages may only contain alphanumeric characters, underscores, and
hyphens.

###### Project Structure
```
project-root/  <-- (usually version control root [eg. git])
  └ php/  <-- (php namespace root)
  | └ me/fru1t/common/  <-- (The fru1tme common library location + this templating plugin)
  | └ your/namespace/  <-- (Your php files)
  |   └ templates/  <-- (Template files you declare)
  |   └ content/  <-- (Folder/Namespace exclusively for publicly available pages)
  └ javascript/  <-- (Whatever Javascript source files you have. This can also reside in /www if
  |                   you don't use a Javascript compiler/substitute [eg. Closure, coffeescript].)
  └ styles/  <-- (Whatever CSS source files you have. This can also reside in /www if you don't use
  |               a CSS compiler/substitute [eg. SASS, Less].)
  └ www/  <-- (Public root. Where apache points to. $_SERVER['DOCUMENT_ROOT'].)
    └ index.php  <-- (Calls all setup and TemplateRouter::openContentFileFromUrl().)
```
This setup encourages separation of your source files (PHP, styles, JS) and publicly accessible
pages.

###### index.php
```
<?php
define("PHP_ROOT", $_SERVER['DOCUMENT_ROOT'] . "/../php");

// This defines the Autoload class which will automate all other class definition searches.
require_once PHP_ROOT . "/me/fru1t/common/language/Autoload";

// Tell PHP where to find things we're using.
use me\fru1t\common\language\Autoload;
use me\fru1t\common\language\Session;
use me\fru1t\common\mysql\MySQL;
use me\fru1t\common\router\Router;

// Autoload MUST be set up first.
Autoload::setup(PHP_ROOT);

// Router should be defined first, so that statically mapped files may be served without the need
// to wait for other modules to setup.
Router::setup()
    ->setContentDirectory("../php/me/fru1t/example/content")
    ->setDefaultContentPagePath("index.php")
    ->setErrorPagePath("error.php")
    ->setPageParameterName(Router::DEFAULT_PAGE_PARAMETER_NAME)
    ->complete();

// Setup all other modules
Session::setup("my-session-name");
MySQL::setup("a.host.example", "a username", "a password", "example database");

// Render content
Router::route();
```
Notice how, because
1. index.php is the only file publicly available (thus is guaranteed to be executed [or error])
2. Plugin setups are done before the content page is executed
We can then guarantee that the content file will have everything it needs (Sessions, database
connections, etc.

#### How it works
`Router::openContentFileFromUrl` requires a "contentPath" parameter. The router will treat
this passed directory path as an extension to the public www/ path, but without the direct access.
This allows all requests to be routed through index.php, but still allows for a proper folder
structure with folder nesting. Due to allowing nested folders, the router has some special handling
with index.php and folders. For example, if a user were to visit `your-domain.tld/?page=some/page`,
the router would attempt to find files in the following precedence:

1. `<content dir>/some/page.php`
2. `<content dir>/some/page/index.php`
3. `<error file>:404`

This means that if there were a file `<content dir>/page/index.php` AND
`<content dir>/some/page.php`. Only the `page.php` file would ever be seen by users.

###### Pretty URLs
Instead of ugly `example.com/?page=mypage`, you want `example.com/mypage`. Simply use an .htaccess
file or similar, in the root public directory, with the following setup:
```
## Enable RewriteEngine from Apache
RewriteEngine On

## Always ignore requests to the root index file.
## L means to stop at this rule if the regex match succeeds
## NC means ignore case
RewriteRule ^index\.php.*$ - [L,NC]

## Prevents rewriting requests of directly accessed files. In other words, the rewrite engine will
## ignore anything that has a file extention (eg. example.com/thing.ext). This is useful for serving
## images or other static content. It's advisable to use a better solution for serving static
## content; however, for many projects, single-server deployment is a good enough solution, which
## is why this line is included as a comment here.
##
## For single file mappings (like javascript files or stylesheets), it's adviseable to use
## `Router::map(Route)`, but not a requirement. This, however, creates a cleaner project structure.
##
## L means to stop at this rule if the regex match succeeds
## NC means ignore case
## Remove the prefixing comment marker '#' to activate.
#RewriteRule ^.+\..+$ - [L,NC]

## Rewrite everything after the root domain slash as a parameter called "page".
## L means to stop at this rule if the regex match succeeds
## QSA means keep any existing parameters mapped (query string append). This removes any leading
##     question marks denoting the start of a query string, and replaces them with an ampersand.
RewriteRule ^([^?]+)(\?.+|)$ /index.php?page=$1 [L,QSA]
```
  
This .htaccess file silently rewrites anything not a direct file access, into a query routed through
index.php. A URL like `example.com/some/page/?foo=bar` would be rewritten internally to be
`example.com/?page=some/page&foo=bar`.  
  
Note that it's possible to change the parameter "page" into something else. One must specify the
parameter name when calling `TemplateRouter::openContentFileFromUrl` under `pageParameterName`.  
  
To learn more about RewriteRule see
[Apache's RewriteRule](https://httpd.apache.org/docs/current/rewrite/flags.html)  

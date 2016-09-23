## `me\fru1t\common\template`
Super simple (fast and lightweight) PHP templating and routing.

#### Why use this?
+ Severely reduce boilerplate code.
+ Eliminate the need for state checking (eg. session starting, database connection handling, etc).
+ URL Routing with pretty URLs.
+ No {{handlebar}} string replacements means FAST dynamic content.
+ User control over rendering allows reusable template fragments (templates within templates within
  templates).
+ MySQL integration enables loading templates directly from a database.
+ JSON requesting for AJAX supporting websites.

#### What can be a template?
Any repeated content (be it headers, footers, table rows, modules, etc) can be abstracted into a
template. It's up to the developer how abstract they want to get. There is no limit! A template
simply holds placeholder variables that are filled in when the template is rendered. User accessible
pages are essentially a template who's rendering is printed (echo'd).

#### Setup
No `#setup` function call is required; however, the command `TemplateRouter::openContentFileFromUrl`
should be executed after all setup calls.

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
use me\fru1t\common\template\TemplateRouter;

// Autoload MUST be set up first.
Autoload::setup(PHP_ROOT);

// Optionally, other plugins.
Session::setup("my-session-name");
MySQL::setup("a.host.example", "a username", "a password", "example database");

// Finally, load our content.
TemplateRouter::openContentFileFromUrl("/../php/me/fru1t/example/", "index.php", "error.php");
```
Notice how, because
1. index.php is the only file publicly available, thus, is always run (or Apache errors)
2. Plugin setups are done before the content is loaded
We can guarantee that the content file will have everything it needs from the get-go without any
`include`s.

#### How it works
`TemplateRouter::openContentFileFromUrl` requires a "contentPath" parameter. The router will treat
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
# Enable RewriteEngine from Apache
RewriteEngine On

# Ignore direct file requests (for things like stylesheets or javascript if they're hosted locally)
# L means to stop at this rule if the regex match succeeds
# NC means ignore case
RewriteRule ^.+\..+$ - [L,NC]

# Rewrite everything after the root domain slash as a parameter called "page".
# L means to stop at this rule if the regex match succeeds
# QSA means keep any existing parameters mapped (query string append). This removes any leading
#     question marks denoting the start of a query string, and replaces them with an ampersand.
RewriteRule ^([^?.]+)(\?.+)?$ /index.php?page=$1 [L,QSA]
```
  
This .htaccess file silently rewrites anything not a direct file access, into a query routed through
index.php. A URL like `example.com/some/page/?foo=bar` would be rewritten internally to be
`example.com/?page=some/page&foo=bar`.  
  
Note that it's possible to change the parameter "page" into something else. One must specify the
parameter name when calling `TemplateRouter::openContentFileFromUrl` under `pageParameterName`.  
  
To learn more about RewriteRule see
[Apache's RewriteRule](https://httpd.apache.org/docs/current/rewrite/flags.html)  

#### Examples
See the `examples` subdirectory.

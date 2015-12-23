# Common Utilities for PHP 7
By Fru1tStudios

### Why use this?
Less boilerplate. More type safety. Easy to use. Very lightweight. Efficient. That's it.

### How it works
Nothing is loaded until specifically invoked, referenced, or `#setup`. Copy what you need, or drop the entire package into your project and use as desired.

### How it's set up
Each direct folder under `common` is an independent library (unless otherwise noted on its respective readme). If required, a library will need to be `#setup` before usage. Otherwise, it's plug and play.

### How to use it with autoloading
1. Use Java-style packaging mechanics (a folder represents a package) and drop `common` into the root php source folder (where ALL of your php lives).  
	For me, this folder is usually `/.site/php` so the project structure will look something like...  
	```
	/.site
	  └ /php  <-- (php namespace root)
	  │  └ /common  <-- (this utility package's contents)
	  │  │  └ Autoload.php
	  │  │  └ ...
	  │  └ /<project-name>  <-- (project-specific root folder)
	  │  │  └ Setup.php
	  │  │  └ ...
	```

2. Create a setup file that handles all the library `#setup`s.  
	I like to drop mine in the project-specific root folder, outlined in step 1's file structure.  
	```
	// Setup.php
	namespace <project>;
	require_once $_SERVER["DOCUMENT_ROOT"] . "/.site/php/common/Autoload.php";
	use common\Autoload;
	use common\mysql\MySQL;
	
	Autoload::setup($_SERVER["DOCUMENT_ROOT"] . "/.site/php", false);
	MySQL::setup(...);
	```

3. `require_once` the setup file at the top of every project-specific file and use away.  
	```
	// StaticContentTemplate.php
	namespace <project>\html\template;
    require_once $_SERVER['DOCUMENT_ROOT'] . '/.site/php/<project>/Setup.php';
    use common\template\component\ContentField;
    use common\template\component\TemplateField;
    use common\template\Content;
    ...
	```

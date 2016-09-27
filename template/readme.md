## `me\fru1t\common\template`
Super simple (fast and lightweight) PHP templating.

#### Why use this?
+ Severely reduce boilerplate code.
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
Entry point: `Templates::setup()`
+ `#enableFormatChanging()` - (optional) Enables the ability for templates to be rendered in other
  formats (like JSON, HTML, etc), selectable via a passed parameter.
+ `#listenForFormatParameterName(string)` - (optional) Requires `#enableFormatChanging()`. Specifies
  the parameter to look for to determine rendering format.
+ `#allowFormatParameterAsGetRequest()` - (optional) Requires `#enableFormatChanging()`. Allows the
  format parameter to be passed as a GET request (in the URL) rather than solely a POST request. In
  the event that both GET and POST are passed, precedence will be given to the POST first.
+ `#complete()` - Completes setup.

For example, a valid set of instructions would be:
```
Templates::setup()
    ->enableFormatChanging()
    ->listenForFormatParameterName("fmt")
    ->complete();
```

#### Examples
See the `examples` subdirectory.

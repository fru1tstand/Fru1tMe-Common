<?php
namespace me\fru1t\common\template\examples\basic;

// Define the HTML that we want to use in the template
$content = <<<HTML
  This is some body content.
  <div>It even has a div in it!</div>
HTML;

// Start the template making process
SimpleTemplate::start()

    // Give it our HTML
    ->with(SimpleTemplate::FIELD_CONTENT, $content)

    // Render it!
    ->render();

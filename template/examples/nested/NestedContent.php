<?php
namespace me\fru1t\common\template\examples\nested;

// Assuming we get these values somehow
$trContent = [
    "Some content 1",
    "A second piece of content",
    "Can I haz a third",
    "Lets go with a fourth"
];

// Creates TRs for the table.
$tdHtml = "";
foreach ($trContent as $content) {
  $tdHtml .= TrTemplate::start()->with(TrTemplate::FIELD_CONTENT, $content)->render(false, true);
}

// And simply pass it along to the Table template. See TableTemplate for an example of templates
// directly calling other templates.
TableTemplate::start()->with(TableTemplate::FIELD_TR_CONTENT, $tdHtml)->render();

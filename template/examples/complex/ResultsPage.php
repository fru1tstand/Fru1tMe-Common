<?php
namespace me\fru1t\common\template\examples\complex;

// Setup query
use me\fru1t\common\language\Param;
use me\fru1t\common\mysql\MySQL;
use me\fru1t\common\mysql\QueryBuilder;

/** @noinspection SqlNoDataSourceInspection */
$rawQuery = "
SELECT
  site.name AS %s, # 1
  site.description AS %s, # 2
  site.url AS %s, # 3
  COUNT(user_action.id) AS %s # 4
FROM site
INNER JOIN user_action ON user_action.site_id = site.id
GROUP BY site.id, site.name, site.description, site.url
WHERE user_action.id = 4 # Likes
HAVING %s > ? # 5 a
ORDER BY %s DESC # 6
";
$query = sprintf($rawQuery,
    ResultTemplate::FIELD_RESULT_NAME, // 1
    ResultTemplate::FIELD_RESULT_DESC, // 2
    ResultTemplate::FIELD_RESULT_URL, // 3
    ResultTemplate::FIELD_RESULT_LIKES, // 4
    ResultTemplate::FIELD_RESULT_LIKES, // 5
    ResultTemplate::FIELD_RESULT_LIKES); // 6

// Execute
$queryResult = MySQL::newQueryBuilder()
    ->withQuery($query)
    ->withParam(Param::fetchGet(ResultTemplate::MIN_LIKES_PARAM) // a
        ?? ResultTemplate::DEFAULT_MIN_LIKES, QueryBuilder::PARAM_TYPE_INT)
    ->build();

// Fetch and render results
$resultHtml = "";
$results = ResultTemplate::createFromQueryResult($queryResult);
foreach ($results as $result) {
  $resultHtml .= $result->render(false, true);
}

// Create and render rest of page
$content = <<<HTML
<div class="results">{$resultHtml}</div>
HTML;
StandardPageTemplate::start()
    ->with(StandardPageTemplate::FIELD_HEAD_TITLE, ResultTemplate::PAGE_TITLE)
    ->with(StandardPageTemplate::FIELD_PAGE_TITLE, ResultTemplate::PAGE_TITLE)
    ->with(StandardPageTemplate::FIELD_PAGE_CONTENT, $content)
    ->render();

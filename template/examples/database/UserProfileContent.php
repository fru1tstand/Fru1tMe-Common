<?php
namespace me\fru1t\common\template\examples\database;
use me\fru1t\common\language\Param;
use me\fru1t\common\mysql\MySQL;

// Setup query with template fields.
/** @noinspection SqlNoDataSourceInspection */
$rawQuery = "
SELECT
  user.username AS %s,
  user.first_name AS %s,
  user.last_name AS %s,
  avatar.url AS %s
FROM user
INNER JOIN avatar ON avatar.id = user.avatar_id
WHERE user.username LIKE ?";
$query = sprintf($rawQuery,
    UserProfileTemplate::FIELD_USERNAME,
    UserProfileTemplate::FIELD_USER_FIRST_NAME,
    UserProfileTemplate::FIELD_USER_LAST_NAME,
    UserProfileTemplate::FIELD_USER_AVATAR_URL);

// Execute query
$queryResult = MySQL::newQueryBuilder()
    ->withQuery($query)
    ->withLikeParam(Param::fetchGet("someParameter"))
    ->build();

// Fetch and render templates.
$profiles = UserProfileTemplate::createFromQueryResult($queryResult);
foreach ($profiles as $profile) {
  $profile->render();
}

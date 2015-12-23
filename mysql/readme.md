## `common\mysql`
Provides abstract mysql interfacing using the MySQLi extension.

#### Setup
`MySQL::setup(...)`. Uses `common\base`.

#### Examples
###### Get a single setting from the database
Traditional method:  
```php
$con = new mysqli(...);
if (!$con) { /* handle error */ }
$stmt = $con->prepare("SELECT value FROM settings WHERE name = ?");
if (!$stmt) { /* handle error */ }
$stmt->bind_param('s', /* setting name */);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows != 1) { /* handle error */ }
$row = $result->fetch_assoc();
echo $row['value'];
```

Fru1tMe Method:  
```php
echo MySQL::newQueryBuilder()
		->withQuery("SELECT `lobby_id` FROM `lobby_post` WHERE `id` = ?")
		->withParam($dbId, QueryBuilder::PARAM_TYPE_INT)
		->build()
		->getResultValue();
```

###### List all users
Traditional method:  
```php
$con = new mysqli(...);
if (!$con) { /* handle error */ }
$stmt = $con->prepare("SELECT username, first_name, last_name FROM users");
if (!$stmt) { /* handle error */ }
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
while ($row = $result->fetch_assoc()) {
	produceUserHtml($row);
}
```

Fru1tMe Method:  
```php
MySQL::newQueryBuilder()
		->withQuery("SELECT username, first_name, last_name FROM users")
		->build()
		->forEachResult(function($row) {
			produceUserHtml($row);
		});
```

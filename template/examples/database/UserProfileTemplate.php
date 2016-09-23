<?php
namespace me\fru1t\common\template\examples\database;
use me\fru1t\common\template\Template;
use me\fru1t\common\template\TemplateField;

/**
 * Defines a simple user profile template.
 */
class UserProfileTemplate extends Template {
  const FIELD_USERNAME = "user-id";
  const FIELD_USER_FIRST_NAME = "user-first-name";
  const FIELD_USER_LAST_NAME = "user-last-name";
  const FIELD_USER_AVATAR_URL = "user-avatar-url";

  const DEFAULT_USER_AVATAR = "default-avatar.jpg";

  /**
   * @param string[] $fields
   * @return string
   */
  public static function getTemplateRenderContents_internal(array $fields): string {
    return <<<HTML
<div class="user-profile">
  <div class="user-username">{$fields[self::FIELD_USERNAME]}</div>
  <div class="user-first-name">{$fields[self::FIELD_USER_FIRST_NAME]}</div>
  <div class="user-last-name">{$fields[self::FIELD_USER_LAST_NAME]}</div>
  <div class="user-avatar"><img src="{$fields[self::FIELD_USER_FIRST_NAME]}"
                                alt="User avatar" /></div>
  
</div>
HTML;
  }

  /**
   * @return TemplateField[]
   */
  static function getTemplateFields_internal(): array {
    return [
        TemplateField::newBuilder()->called(self::FIELD_USERNAME)->asRequired()->build(),
        TemplateField::newBuilder()->called(self::FIELD_USER_FIRST_NAME)->asRequired()->build(),
        TemplateField::newBuilder()->called(self::FIELD_USER_LAST_NAME)->asRequired()->build(),
        TemplateField::newBuilder()->called(self::FIELD_USER_AVATAR_URL)
            ->defaultingTo(self::DEFAULT_USER_AVATAR)->build()
    ];
  }
}

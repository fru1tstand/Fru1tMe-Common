<?php
namespace me\fru1t\common\language;

/**
 * Defines HTTP related constants (enums).
 */
class Http {
  // Status codes.
  const STATUS_OK = 200;
  const STATUS_NOT_FOUND = 404;

  // Common MIME types
  const HEADER_CONTENT_TYPE_CSS = 'Content-Type: text/css';
  const HEADER_CONTENT_TYPE_JAVASCRIPT = 'Content-Type: application/javascript';
  const HEADER_CONTENT_TYPE_JSON = 'Content-Type: application/json';
}

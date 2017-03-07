<?php
namespace me\fru1t\common\template;
use me\fru1t\common\language\Param;
use me\fru1t\common\language\Preconditions;
use RuntimeException;

/**
 * Provides setup and settings management for the Fru1tme templating system.
 */
final class Templates {
  /** Indicates that JSON should be returned. */
  const FORMAT_JSON = "json";
  /** Indicates that HTML should be returned. */
  const FORMAT_HTML = "html";
  /** @var array Contains all valid formats. */
  private static $validFormats = [
      self::FORMAT_HTML, self::FORMAT_JSON
  ];

  /**
   * The format parameter controls the type of output templates should display (eg. json, html, etc)
   */
  const DEFAULT_FORMAT_PARAMETER_NAME = "fmt";

  /** @var null|Templates */
  private static $templates = null;

  /**
   * Returns the render format specified by the requester if the feature is enabled. This method
   * will ALWAYS return a valid render format specified by the constants in this class prefixed with
   * "FORMAT_*". Defaults to (@link Templates::FORMAT_HTML} if parameters are invalid or feature is
   * disabled.
   * @return string
   */
  public static function getRenderFormat(): string {
    // $templates is valid after this line.
    self::checkSetup();

    // Is enabled?
    if (!self::$templates->isFormatChangingEnabled) {
      return self::FORMAT_HTML;
    }

    // Check POST
    $param = Param::fetchPost(self::$templates->formatParameterName);
    if (Preconditions::isAnyOf($param, self::$validFormats)) {
      return $param;
    }

    // Check GET if enabled.
    $param = Param::fetchPost(self::$templates->formatParameterName);
    if (self::$templates->allowFormatParameterAsGetRequest
        && Preconditions::isAnyOf($param, self::$validFormats)) {
      return $param;
    }

    return self::FORMAT_HTML;
  }

  private static function checkSetup(): void {
    if (Preconditions::isNull(self::$templates)) {
      throw new RuntimeException("Templates was never set up. Please see Templates::setup().");
    }
  }

  /**
   * Starts the setup process for Template settings.
   * @return Templates
   */
  public static function setup(): Templates {
    return new Templates();
  }

  /** Private constructor. One should use #setup(). */
  private function __construct() {
    $this->isFormatChangingEnabled = false;
    $this->formatParameterName = null;
    $this->allowFormatParameterAsGetRequest = false;
  }

  /** @var bool */
  private $isFormatChangingEnabled;
 /** @var null|string */
  private $formatParameterName;
  /** @var bool */
  private $allowFormatParameterAsGetRequest;

  /**
   * Enables the output format to change from HTML to JSON or other implemented formats. This is
   * useful for AJAX loading.
   * @return Templates
   */
  public function enableFormatChanging(): Templates {
    $this->isFormatChangingEnabled = true;
    return $this;
  }

  /**
   * Sets the templating engine to listen to this parameter to handle return formats. (eg. JSON,
   * HTML, etc).
   * @param string $formatParameterName
   * @return Templates
   */
  public function listenForFormatParameterName(string $formatParameterName): Templates {
    $this->formatParameterName = $formatParameterName;
    return $this;
  }

  /**
   * Allows the format parameter to be passed as GET request (in the URL) instead of solely POST. If
   * both parameters are passed, precedence will be given to the POST parameter.
   * @return Templates
   */
  public function allowFormatParameterAsGetRequest(): Templates {
    $this->allowFormatParameterAsGetRequest = true;
    return $this;
  }

  /**
   * Finalizes (and validates) setup of Template settings.
   */
  public function complete(): void {
    if ($this->isFormatChangingEnabled
        && Preconditions::isNullEmptyOrWhitespace($this->formatParameterName)) {
      throw new RuntimeException("Format changing is enabled, but is not set up complete. Please"
          . " use #listenForFormatParameterName(string) to set a parameter name.");
    }

    self::$templates = $this;
  }
}

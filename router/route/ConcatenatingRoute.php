<?php
namespace me\fru1t\common\router\route;

/**
 * Defines a static route that maps a single input to a concatenation of zero or more files.
 */
class ConcatenatingRoute extends StaticRoute {
  /** @var string[] */
  private $filePaths;
  /** @var string */
  private $requestUrl;

  /**
   * Creates a new ConcatenatingRoute object that maps the a static url to the contents of zero or
   * more files. File content is concatenated in the same order given in the passed array.
   * @param string $requestUrl The URL to respond to.
   * @param array $filePaths The files to concatenate.
   * @param bool $isCaseSensitive Optional
   * @param array $headers Optional Any headers to be sent before the file payloads.
   */
  public function __construct(string $requestUrl, array $filePaths, bool $isCaseSensitive = true, array $headers = []) {
    parent::__construct($requestUrl, $isCaseSensitive, $headers);
    $this->filePaths = $filePaths;
    $this->requestUrl = $requestUrl;
  }

  /**
   * Outputs this route's payload via echo, include, print, etc.
   */
  protected function resolve(): void {
    foreach ($this->filePaths as $filePath) {
      /** @noinspection PhpIncludeInspection */
      include($filePath);
    }
  }
}

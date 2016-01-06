<?php
/**
 * @file
 * Responder class.
 */

/**
 * Class Responder.
 */
class Responder {

  /**
   * Array of directives.
   *
   * @var array
   */
  private $directives;

  /**
   * Responder constructor.
   */
  public function __construct() {
    $this->directives = [];
  }

  /**
   * Adds directive.
   *
   * @param string $text
   *   Raw untranslated string with placeholders.
   *
   * @param string[] $placeholders
   *   Array of placeholder strings.
   */
  public function push($text, $placeholders = []) {
    $this->directives[] = [
      'say' => strtr($text, $placeholders),
    ];
  }

  /**
   * Generates XML response.
   *
   * @return string
   *   XML of response.
   */
  public function toXml() {
    $xml = new DOMDocument();

    $xml->appendChild($script = $xml->createElement('Response'));

    array_map(function ($directive) use ($script, $xml) {
      $script->appendChild($xml->createElement(ucfirst(key($directive)), current($directive)));
    }, $this->directives);

    return $xml->saveXML();
  }

}

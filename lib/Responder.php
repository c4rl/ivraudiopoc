<?php

class Responder {

  private $directives;

  public function __construct() {
    $this->directives = [];
  }

  public function push($text, $placeholders = []) {

    $this->directives[] = [
      'say' => strtr($text, $placeholders),
    ];

  }

  public function toXml() {
    $xml = new DOMDocument();
    $xml->appendChild($script = $xml->createElement('Response'));
    array_map(function ($directive) use ($script, $xml) {
      $script->appendChild($xml->createElement(ucfirst(key($directive)), current($directive)));
    }, $this->directives);
    return $xml->saveXML();
  }

}

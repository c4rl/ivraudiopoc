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
   * Default adaptor.
   */
  const DEFAULT_ADAPTOR = 'text';

  /**
   * Array of directives.
   *
   * @var array
   */
  private $directives;

  /**
   * Preferred adaptor.
   *
   * @var string
   */
  private $preferred_adaptor;

  /**
   * Dictionary of terms.
   *
   * @var stdClass[]
   */
  private $dictionary;

  /**
   * Responder constructor.
   *
   * @param string $preferred_adaptor
   *   Preferred adaptor, either "text" or "audio"
   *
   * @param stdClass[] $dictionary
   *   Dictionary of text and audio directives, keyed by text.
   */
  public function __construct($preferred_adaptor = 'text', $dictionary = []) {
    $this->directives = [];
    $this->preferred_adaptor = $preferred_adaptor;
    $this->dictionary = $dictionary;
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

    $entry = $this->dictionary[$text];

    if (isset($entry->{$this->preferred_adaptor})) {
      $adaptor = $this->preferred_adaptor;
    }
    elseif (isset($entry->{self::DEFAULT_ADAPTOR})) {
      $adaptor = self::DEFAULT_ADAPTOR;
    }
    else {
      throw new Exception('No available value!');
    }

    switch ($adaptor) {
      case 'text':
        $this->directives[] = [
          'say' => strtr($entry->text, $placeholders),
        ];
        break;
      case 'audio':
        if (is_array($entry->audio)) {
          $this->directives = array_merge($this->directives, self::parseAudioDirectives($entry->audio, $placeholders));
        }
        else {
          $this->directives = array_merge($this->directives, self::parseAudioDirectives([
            $entry->audio,
          ], $placeholders));
        }
        break;
      default:
        throw new Exception('Unknown adaptor!');
    }
  }

  /**
   * Generates XML response.
   *
   * @return string
   *   XML of response.
   */
  public function toXml() {
    $xml = new DOMDocument();
    $xml->formatOutput = TRUE;

    $xml->appendChild($script = $xml->createElement('Response'));

    array_map(function ($directive) use ($script, $xml) {
      $script->appendChild($xml->createElement(ucfirst(key($directive)), current($directive)));
    }, $this->directives);

    return $xml->saveXML();
  }

  /**
   * Parses array of audio assets and placeholders into directives.
   *
   * @param array $audio
   *   An array of audio directives, some placeholders or files.
   *
   * @param array $placeholders
   *   Array of placeholders.
   *
   * @return array
   *   Array of directives, with either "say" or "play" commands.
   */
  static public function parseAudioDirectives(array $audio, array $placeholders = []) {
    return array_map(function ($item) use ($placeholders) {

      // For placeholder key, use robot voice.
      if (array_key_exists($item, $placeholders)) {
        return [
          'say' => $placeholders[$item],
        ];
      }

      // Assume audio file.
      return [
        'play' => $item,
      ];

    }, $audio);
  }

}

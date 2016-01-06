# IVR Audio Proof of Concept

Allows for both robot text and audio files.

See `script.json` for dictionary format.

### Usage: text

    $ php -f index.php text
    <?xml version="1.0"?>
    <Response>
      <Say>Hello</Say>
      <Say>Your Garfield card has balance of $12345.67</Say>
    </Response>

### Usage: audio

    $ php -f index.php audio
    <?xml version="1.0"?>
    <Response>
      <Play>HELLO.aiff</Play>
      <Play>YOUR.aiff</Play>
      <Say>Garfield</Say>
      <Play>CARD_HAS_BALANCE_OF.aiff</Play>
      <Say>$12345.67</Say>
    </Response>


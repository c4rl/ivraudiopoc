<?php

include './lib/Responder.php';
include './lib/AudibleString.php';

if (!isset($argv[1]) || !in_array($argv[1], [
    'text',
    'audio',
  ])) {
  die("ERROR You must supply an argument.
    Usage:
    $ php -f index.php [text|audio]\n");
}
else {
  $adaptor = $argv[1];
}

// Build dictionary.
$dictionary = [];
foreach (json_decode(file_get_contents('./script.json')) as $entry) {
  $dictionary[$entry->text] = $entry;
}

// Initialize responder.
$responder = new Responder($adaptor, $dictionary);

// Create some sample arguments and text.
$artwork = 'Garfield';
$balance = '$12345.67';

$responder->push('Hello');
$responder->push('Your @artwork card has balance of @balance', [
  '@artwork' => $artwork,
  '@balance' => AudibleString::currencyToWords($balance),
]);

echo $responder->toXml();

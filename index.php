<?php

include './lib/Responder.php';

$responder = new Responder();

$artwork = 'Garfield';
$balance = 12345.67;

$responder->push('Hello');
$responder->push('Your @artwork card has balance of @balance', [
  '@artwork' => $artwork,
  '@balance' => $balance,
]);

echo $responder->toXml();

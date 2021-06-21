<?php

require_once __DIR__ . '/vendor/autoload.php';

use TCGdex\TCGdex;

$tcgdex = new TCGdex("en");
/** @var string[] */
$cards = $tcgdex->fetchHp();
echo $cards;
var_dump($tcgdex->fetchHp($cards[0]));
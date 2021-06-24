<?php

declare(strict_types=1);

namespace Tests;

use TCGdex\TCGdex;
use PHPUnit\Framework\TestCase;

final class StackTest extends TestCase
{

    public function testCanRequest(): void
    {
        TCGdex::$client = new Psr18Mock("{\"ok\": true}");
        $tcgdex = new TCGdex("en");
        $card = $tcgdex->fetchCard('testCanRequest');
        $this->assertNotEmpty($card);
    }

    public function test404Error(): void
    {
        TCGdex::$client = new Psr18Mock("{\"ok\": true}", 404);
        $tcgdex = new TCGdex("en");
        $card = $tcgdex->fetchCard('test404Error');
        $this->assertEmpty($card);
    }

    public function testCache(): void
    {
        TCGdex::$client = new Psr18Mock("{\"id\": \"1\"}");
        $tcgdex = new TCGdex("en");
        $card1 = $tcgdex->fetchCard('testCache');
        $this->assertEquals($card1->id, "1");
        TCGdex::$client = new Psr18Mock("{\"id\": \"2\"}");
        $card2 = $tcgdex->fetchCard('testCache');
        $this->assertEquals($card2->id, "1");
    }

    public function testRealEndpoints(): void
    {
        TCGdex::$client = null;
        $tcgdex = new TCGdex("en");
        $endpoints = array(
            array('fetchCard', array('swsh1-1')),
            array('fetchCard', array('1', 'swsh1')),
            array('fetchCards', array('swsh1')),
            array('fetchCards', array()),
            array('fetchSet', array('swsh1')),
            array('fetchSets', array('swsh1')),
            array('fetchSets', array('swsh')),
            array('fetchSeries', array()),
            array('fetchSerie', array('swsh')),
        );
        foreach ($endpoints as $item) {
            $this->assertNotEmpty($tcgdex->{$item[0]}(...$item[1]));
        }
    }
}

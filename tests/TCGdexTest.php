<?php

declare(strict_types=1);

namespace Tests;

use TCGdex\TCGdex;
use PHPUnit\Framework\TestCase;
use TCGdex\Query;

final class TCGdexTest extends TestCase
{

    public function testCanRequest(): void
    {
        TCGdex::$client = new Psr18Mock("{\"ok\": true}");
        $tcgdex = new TCGdex("en");
        $card = $tcgdex->card->get('testCanRequest');
        $this->assertNotEmpty($card);
    }

    public function test404Error(): void
    {
        TCGdex::$client = new Psr18Mock("{\"ok\": true}", 404);
        $tcgdex = new TCGdex("en");
        $card = $tcgdex->card->get('test404Error');
        $this->assertEmpty($card);
    }

    public function testCache(): void
    {
        TCGdex::$client = new Psr18Mock("{\"id\": \"1\"}");
        $tcgdex = new TCGdex("en");
        $card1 = $tcgdex->card->get('testCache');
        $this->assertEquals("1", $card1->id);
        TCGdex::$client = new Psr18Mock("{\"id\": \"2\"}");
        $card2 = $tcgdex->card->get('testCache');
        $this->assertEquals("1", $card2->id);
    }

    public function testRealEndpoints(): void
    {
        TCGdex::$client = null;
        $tcgdex = new TCGdex("en");

        $endpoints = array(
            $tcgdex->card,
            $tcgdex->variant,
            $tcgdex->trainerType,
            $tcgdex->suffix,
            $tcgdex->stage,
            $tcgdex->regulationMark,
            $tcgdex->energyType,
            $tcgdex->dexId,
            $tcgdex->type,
            $tcgdex->set,
            $tcgdex->serie,
            $tcgdex->retreat,
            $tcgdex->rarity,
            $tcgdex->illustrator,
            $tcgdex->hp,
            $tcgdex->category,
        );
        foreach ($endpoints as $item) {
            $list = $item->list();
            $this->assertNotEmpty($list);
            $first = $list[0];
            if (gettype($first) === "string" || gettype($first) === "integer") {
                $this->assertNotEmpty($item->get($first));
            } else {
                $this->assertNotEmpty($item->get($first->id));
            }
        }
    }

    public function testUnknownCard(): void
    {
        TCGdex::$client = null;
        $tcgdex = new TCGdex("en");
        $card = $tcgdex->set->getCard('unknownSet', 'unknownCard');

        $this->assertNull($card);
    }

    public function testFetchFullCardFromResume(): void
    {
        TCGdex::$client = null;
        $tcgdex = new TCGdex("en");
        $cards = $tcgdex->card->list(Query::create()
        ->equal('name', 'Furret'));
        $this->assertNotEmpty($cards);
        $this->assertNotEmpty($cards[0]->toCard());
    }

    public function testFetchFullSerieFromResume(): void
    {
        TCGdex::$client = null;
        $tcgdex = new TCGdex("en");
        $series = $tcgdex->serie->list();
        $this->assertNotEmpty($series);
        $this->assertNotEmpty($series[0]->toSerie());
    }
}

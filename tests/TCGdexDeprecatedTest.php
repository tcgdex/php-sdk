<?php

declare(strict_types=1);

namespace Tests;

use TCGdex\TCGdex;
use PHPUnit\Framework\TestCase;

final class TCGdexDeprecatedTest extends TestCase
{
    public function testCanRequest(): void
    {
        TCGdex::$client = new Psr18Mock("{\"id\": \"swsh1-136\"}");
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
            array('fetchCard', array('swsh3-136')),
            array('fetchCard', array('136', 'swsh3')),

            array('fetchCards', array('swsh3')),
            array('fetchCards', array()),
            array('fetchSet', array('swsh3')),
            array('fetchSets', array('swsh3')),
            array('fetchSets', array('swsh')),
            array('fetchSeries', array()),
            array('fetchSerie', array('swsh')),
            array('fetchType', array('Colorless')),

            array('fetchTypes', array()),
            array('fetchRetreat', array('1')),
            array('fetchRetreats', array()),
            array('fetchRarity', array('Uncommon')),
            array('fetchRarities', array()),
            array('fetchIllustrator', array('tetsuya koizumi')),
            array('fetchIllustrators', array()),
            array('fetchHp', array('110')),
            array('fetchHps', array()),
            array('fetchCategory', array('Pokemon')),
            array('fetchCategories', array()),

            array('fetchDexId', array('162')),
            array('fetchDexIds', array()),
            array('fetchEnergyType', array('Special')),
            array('fetchEnergyTypes', array()),
            array('fetchRegulationMark', array('D')),
            array('fetchRegulationMarks', array()),
            array('fetchStage', array('Basic')),
            array('fetchStages', array()),

            array('fetchSuffix', array('EX')),
            array('fetchSuffixes', array()),
            array('fetchTrainerType', array('Tool')),
            array('fetchTrainerTypes', array()),
            array('fetchVariant', array('holo')),
            array('fetchVariants', array()),
        );
        foreach ($endpoints as $item) {
            $this->assertNotEmpty($tcgdex->{$item[0]}(...$item[1]));
        }
    }

    public function testFetchFullCardFromResume(): void
    {
        TCGdex::$client = null;
        $tcgdex = new TCGdex("en");
        $cards = $tcgdex->fetchCards('swsh1');
        $this->assertNotEmpty($cards);
        $this->assertNotEmpty($cards[0]->fetchFullCard());
    }

    public function testFetchFullSerieFromResume(): void
    {
        TCGdex::$client = null;
        $tcgdex = new TCGdex("en");
        $series = $tcgdex->fetchSeries();
        $this->assertNotEmpty($series);
        $this->assertNotEmpty($series[0]->fetchFullSerie());
    }
}

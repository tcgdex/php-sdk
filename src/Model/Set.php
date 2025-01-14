<?php

namespace TCGdex\Model;

use TCGdex\Model\SubModel\CardCount;
use TCGdex\Model\SubModel\Variants;
use TCGdex\Model\SubModel\Legal;
use TCGdex\Model\SubModel\Abbreviation;

class Set extends SetResume
{
    /**
     * the serie the set is part of
     */
    public SerieResume $serie;

    /**
     * the TCG Online ID
     */
    public ?string $tcgOnline = null;

    /**
     * @deprecated this variable is inexistant in the API
     */
    public ?Variants $variants = null;

    /**
     * the set release date as an ISO8601 string (ex: `2020-02-01`)
     */
    public string $releaseDate = '';

    /**
     * Designate if the set is usable in tournaments
     *
     * Note: this is specific to the set and if a
     * card is banned from the set it will still be true
     */
    public Legal $legal;

    /**
     * the number of cards of the set in total & by variant
     * @var CardCount
     */
    public $cardCount;

    /**
     * The official and localized abbreviation used by TPC
     */
    public Abbreviation $abbreviation;

    /**
     * the list of cards of the set
     * @var CardResume[]
     */
    public array $cards = [];

    protected function fill(object $data): void
    {
        foreach ($data as $key => $value) {
            if ($key === 'cardCount') {
                $this->cardCount = Model::build(new CardCount($this->sdk), $value);
            } elseif ($key === 'serie') {
                $this->serie = Model::build(new SerieResume($this->sdk), $value);
            } elseif ($key === 'abbreviation') {
                $this->abbreviation = Model::build(new Abbreviation($this->sdk), $value);
            } elseif ($key === 'legal') {
                $this->legal = Model::build(new Legal($this->sdk), $value);
            } elseif ($key === 'cards') {
                $this->cards = array_map(function ($item) {
                    return Model::build(new CardResume($this->sdk), $item);
                }, $value);
            } else {
                $this->{$key} = $value;
            }
        }
    }
}

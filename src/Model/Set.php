<?php

namespace TCGdex\Model;

use TCGdex\Model\SubModel\CardCount;
use TCGdex\Model\SubModel\Variants;
use stdClass;
use TCGdex\Model\SubModel\Legal;

class Set extends SetResume
{
    /**
     * @var SerieResume
     */
    public $serie;

    /**
     * @var string|null
     */
    public $tcgOnline;


    /**
     * @var Variants|null
     */
    public $variants;

    /**
     * @var string
     */
    public $releaseDate;

    /**
     * @var Legal
     */
    public $legal;

    /**
     * @var CardCount
     */
    public $cardCount;

    /**
     * @var CardResume[]
     */
    public $cards;

    protected function fill(object $data): void
    {
        foreach ($data as $key => $value) {
            if ($key === 'cardCount') {
                $this->cardCount = Model::build(new CardCount($this->sdk), $value);
            } elseif ($key === 'serie') {
                $this->serie = Model::build(new SerieResume($this->sdk), $value);
            } elseif ($key === 'variants') {
                $this->variants = Model::build(new Variants($this->sdk), $value);
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

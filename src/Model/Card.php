<?php

namespace TCGdex\Model;

use stdClass;

/**
 *
 * Remove PHPMD warning about the number of fields
 * @SuppressWarnings("fields")
 */
class Card extends CardResume
{

    protected function fill(stdClass $data)
    {
        foreach ($data as $key => $value) {
            if ($key === 'set') {
                $this->set = Model::build(new SetResume($this->sdk), $value);
            } else {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @var string|null
     */
    public $illustrator;

    /**
     * @var string
     */
    public $rarity;

    /**
     * @var string
     */
    public $category;

    public $variants;

    /**
     * @var CardResume
     */
    public $set;

    /**
     * @var int|null
     */
    public $dexId;

    /**
     * @var int|null
     */
    public $hp;
    /**
     * @var string[]|null
     */
    public $types;

    /**
     * @var string|null
     */
    public $evolveFrom;

    /**
     * Temporarly not implemented due to #28
     */
    // public $weight;
    // public $height;

    /**
     * @var string|null
     */
    public $description;

    /**
     * @var string|null
     */
    public $level;

    /**
     * @var string|null
     */
    public $stage;

    /**
     * @var string|null
     */
    public $suffix;

    public $item;

    public $abilities;

    public $attacks;

    public $weaknesses;

    public $resistances;

    /**
     * @var int|null
     */
    public $retreat;

    /**
     * @var string|null
     */
    public $effect;

    /**
     * @var string|null
     */
    public $trainerType;

    /**
     * @var string|null
     */
    public $energyType;

    /**
     * @var string|null
     */
    public $regulationMark;

    public $legal;

    public $fetchFullCard = null;
}

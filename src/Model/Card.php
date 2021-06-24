<?php

namespace TCGdex\Model;

use stdClass;
use TCGdex\Model\SubModel\Variants;
use TCGdex\Model\SubModel\Legal;
use TCGdex\Model\SubModel\Ability;
use TCGdex\Model\SubModel\Attack;
use TCGdex\Model\SubModel\Item;
use TCGdex\Model\SubModel\WeakRes;

/**
 *
 * Remove PHPMD warning about the number of fields
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity) Temporary
 */
class Card extends CardResume
{

    protected function fill(object $data): void
    {
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'set':
                    $this->{$key} = Model::build(new SetResume($this->sdk), $value);
                    break;
                case 'variants':
                    $this->{$key} = Model::build(new Variants($this->sdk), $value);
                    break;
                case 'item':
                    $this->{$key} = Model::build(new Item($this->sdk), $value);
                    break;
                case 'abilities':
                    $this->{$key} = array_map(function ($item) {
                        return Model::build(new Ability($this->sdk), $item);
                    }, $value);
                    break;
                case 'attacks':
                    $this->{$key} = array_map(function ($item) {
                        return Model::build(new Attack($this->sdk), $item);
                    }, $value);
                    break;
                case 'weaknesses':
                case 'resistances':
                    $this->{$key} = array_map(function ($item) {
                        return Model::build(new WeakRes($this->sdk), $item);
                    }, $value);
                    break;
                case 'legal':
                    $this->{$key} = Model::build(new Legal($this->sdk), $value);
                    break;
                default:
                    $this->{$key} = $value;
                    break;
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

    /**
     * @var Variants
     */
    public $variants;

    /**
     * @var SetResume
     */
    public $set;

    /**
     * @var int[]|null
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
     * @var string|int|null
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

    /**
     * @var Item|null
     */
    public $item;

    /**
     * @var Ability[]
     */
    public $abilities;

    /**
     * @var Attack[]
     */
    public $attacks;

    /**
     * @var WeakRes[]
     */
    public $weaknesses;

    /**
     * @var WeakRes[]
     */
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

    /**
     * @var Legal
     */
    public $legal;

    /**
     * @var null
     */
    public $fetchFullCard = null;
}

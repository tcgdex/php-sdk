<?php

namespace TCGdex\Model;

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

    public ?string $illustrator = null;

    public string $rarity = '';

    public string $category = '';

    public Variants $variants;

    public SetResume $set;

    /**
     * @var int[]
     */
    public ?array $dexId = null;

    public ?int $hp = null;

    /**
     * @var string[]
     */
    public ?array $types = null;

    public ?string $evolveFrom = null;

    /**
     * Temporarly not implemented due to #28
     */
    // public $weight;
    // public $height;

    public ?string $description = null;

    public string|int|null $level = null;

    public ?string $stage = null;

    public ?string $suffix = null;

    public ?Item $item = null;

    /**
     * @var Ability[]
     */
    public array $abilities = [];

    /**
     * @var Attack[]
     */
    public array $attacks = [];

    /**
     * @var WeakRes[]
     */
    public array $weaknesses = [];

    /**
     * @var WeakRes[]
     */
    public array $resistances = [];

    public ?int $retreat = null;

    public ?string $effect = null;

    public ?string $trainerType = null;

    public ?string $energyType = null;

    public ?string $regulationMark = null;

    public Legal $legal;
}

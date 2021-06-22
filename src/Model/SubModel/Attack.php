<?php

namespace TCGdex\Model\SubModel;

use TCGdex\Model\Model;

class Attack extends Model
{
    /**
     * @var string[]|null
     */
    public $cost;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string|null
     */
    public $effect;

    /**
     * @var string|int|null
     */
    public $damage;
}

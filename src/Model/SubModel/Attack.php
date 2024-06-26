<?php

namespace TCGdex\Model\SubModel;

use TCGdex\Model\Model;

class Attack extends Model
{
    /**
     * @var string[]
     */
    public ?array $cost = null;

    public string $name = '';

    public ?string $effect = null;

    public string|int|null $damage = null;
}

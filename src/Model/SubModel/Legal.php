<?php

namespace TCGdex\Model\SubModel;

use TCGdex\Model\Model;

class Legal extends Model
{
    /**
     * Ability to play in standard tournaments
     */
    public bool $standard;

    /**
     * Ability to play in expanded tournaments
     */
    public bool $expanded;
}

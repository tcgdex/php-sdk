<?php

namespace TCGdex\Model\SubModel;

use TCGdex\Model\Model;

class CardCountResume extends Model
{
    /**
     * total of number of cards
     */
    public int $total = 0;

    /**
     * number of cards officialy (on the bottom of each cards)
     */
    public int $official = 0;
}

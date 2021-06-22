<?php

namespace TCGdex\Model\SubModel;

class CardCount extends CardCountResume
{
    /**
     * @var int
     */
    public $normal;

    /**
     * @var int
     */
    public $reverse;

    /**
     * @var int
     */
    public $holo;

    /**
     * @var int|null
     */
    public $firstEd;
}

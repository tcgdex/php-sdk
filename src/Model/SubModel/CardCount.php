<?php

namespace TCGdex\Model\SubModel;

class CardCount extends CardCountResume
{
    /**
     * number of cards having a normal version
     */
    public int $normal = 0;

    /**
     * number of cards having an reverse version
     */
    public int $reverse = 0;

    /**
     * number of cards having an holo version
     */
    public int $holo = 0;

    /**
     * Number of cards that can have the first edition tag
     */
    public ?int $firstEd = null;
}

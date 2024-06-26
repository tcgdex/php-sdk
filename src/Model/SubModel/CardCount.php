<?php

namespace TCGdex\Model\SubModel;

class CardCount extends CardCountResume
{
    public int $normal = 0;

    public int $reverse = 0;

    public int $holo = 0;

    public ?int $firstEd = null;
}

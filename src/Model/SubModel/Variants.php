<?php

namespace TCGdex\Model\SubModel;

use TCGdex\Model\Model;

class Variants extends Model
{
    public ?bool $normal = null;

    public ?bool $reverse = null;

    public ?bool $holo = null;

    public ?bool $firstEdition = null;

    public ?bool $wPromo = null;
}

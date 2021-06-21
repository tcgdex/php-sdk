<?php

namespace TCGdex\Model;

use stdClass;

class SetResume extends Model
{

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string|null
     */
    public $logo;

    /**
     * @var string|null
     */
    public $symbol;
    public $cardCount;
}

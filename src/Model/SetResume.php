<?php

namespace TCGdex\Model;

use TCGdex\Model\SubModel\CardCountResume;

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

    /**
     * @var CardCountResume
     */
    public $cardCount;

    protected function fill(object $data): void
    {
        foreach ($data as $key => $value) {
            if ($key === 'cardCount') {
                $this->cardCount = Model::build(new CardCountResume($this->sdk), $value);
            } else {
                $this->{$key} = $value;
            }
        }
    }
}

<?php

namespace TCGdex\Model;

class CardResume extends Model
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $localId;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string|null
     */
    public $image;

    public function fetchFullCard(): Card
    {
        return $this->sdk->fetchCard($this->id);
    }
}

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

    /**
     * @deprecated 2.2.0 use `toCard()` instead
     */
    public function fetchFullCard(): Card
    {
        return $this->toCard();
    }

    public function toCard(): Card
    {
        return $this->sdk->card->get($this->id);
    }
}

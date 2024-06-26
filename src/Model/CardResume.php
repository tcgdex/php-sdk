<?php

namespace TCGdex\Model;

class CardResume extends Model
{
    public string $id = '';

    public string $localId = '';

    public string $name = '';

    public ?string $image = null;

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

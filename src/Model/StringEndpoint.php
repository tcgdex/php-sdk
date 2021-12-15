<?php

namespace TCGdex\Model;

class StringEndpoint extends Model
{
    protected function fill(object $data): void
    {
        foreach ($data as $key => $value) {
            if ($key === 'cards') {
                $this->cards = array_map(function ($item) {
                    return Model::build(new CardResume($this->sdk), $item);
                }, $value);
            } else {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @var string
     */
    public $name;

    /**
     * @var \TCGdex\Model\CardResume[]
     */
    public $cards;
}

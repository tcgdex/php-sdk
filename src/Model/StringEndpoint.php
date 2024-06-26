<?php

namespace TCGdex\Model;

class StringEndpoint extends Model
{
    public string $name = '';

    /**
     * @var CardResume[]
     */
    public array $cards = [];

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
}

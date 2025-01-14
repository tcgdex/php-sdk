<?php

namespace TCGdex\Model;

class Serie extends SerieResume
{
    /**
     * @var SetResume[]
     */
    public array $sets = [];

    public ?SetResume $firstSet = null;
    public ?SetResume $lastSet = null;

    protected function fill(object $data): void
    {
        foreach ($data as $key => $value) {
            if ($key === 'sets') {
                $this->sets = array_map(function ($item) {
                    return Model::build(new SetResume($this->sdk), $item);
                }, $value);
            } elseif ($key === 'firstSet' || $key === 'lastSet') {
                $this->{$key} = Model::build(new SetResume($this->sdk), $value);
            } else {
                $this->{$key} = $value;
            }
        }
    }
}

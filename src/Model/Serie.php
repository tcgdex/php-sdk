<?php

namespace TCGdex\Model;

use stdClass;

class Serie extends SerieResume
{

    /**
     * @var SetResume[]
     */
    public $sets;

    protected function fill(stdClass $data)
    {
        foreach ($data as $key => $value) {
            if ($key === 'sets') {
                $this->sets = array_map(function ($item) {
                    return Model::build(new SetResume($this->sdk), $item);
                }, $value);
            } else {
                $this->{$key} = $value;
            }
        }
    }
}

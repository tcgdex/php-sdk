<?php

namespace TCGdex\Model;

class SerieResume extends Model
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
     * @deprecated 2.2.0 use `toSerie()` instead
     */
    public function fetchFullSerie(): Serie
    {
        return $this->toSerie();
    }

    /**
     * @return Serie
     */
    public function toSerie(): Serie
    {
        return $this->sdk->serie->get($this->id);
    }
}

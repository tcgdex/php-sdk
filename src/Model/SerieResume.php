<?php

namespace TCGdex\Model;

class SerieResume extends Model
{
    public string $id = '';

    public string $name = '';

    public ?string $logo = null;

    /**
     * @deprecated 2.2.0 use `toSerie()` instead
     */
    public function fetchFullSerie(): Serie
    {
        return $this->toSerie();
    }

    public function toSerie(): Serie
    {
        return $this->sdk->serie->get($this->id);
    }
}

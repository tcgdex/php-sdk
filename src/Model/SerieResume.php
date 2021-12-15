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
     * @return Serie
     */
    public function fetchFullSerie()
    {
        return $this->sdk->fetchSerie($this->id);
    }
}

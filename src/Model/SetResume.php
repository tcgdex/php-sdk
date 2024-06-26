<?php

namespace TCGdex\Model;

use TCGdex\Model\SubModel\CardCountResume;

class SetResume extends Model
{
    public string $id = '';

    public string $name = '';

    public ?string $logo = null;

    public ?string $symbol = null;

    /**
     * @var CardCountResume
     */
    public $cardCount;

    protected function fill(object $data): void
    {
        foreach ($data as $key => $value) {
            if ($key === 'cardCount') {
                $this->cardCount = Model::build(new CardCountResume($this->sdk), $value);
            } else {
                $this->{$key} = $value;
            }
        }
    }

    public function toSet(): Set
    {
        return $this->sdk->set->get($this->id);
    }
}

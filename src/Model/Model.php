<?php

namespace TCGdex\Model;

use stdClass;

abstract class Model
{

    /**
     * @var \TCGdex\TCGdex
     */
    protected $sdk;

    /**
     * @param \TCGdex\TCGdex $sdk
     */
    public function __construct(&$sdk)
    {
        $this->sdk = $sdk;
    }

    public static function build(Model $model, stdClass $data)
    {
        $model->fill($data);
        return $model;
    }

    /**
     * Basic implementation
     * ```
     * foreach ($data AS $key => $value) $this->{$key} = $value;
     * ```
     */
    protected function fill(stdClass $data)
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }
}

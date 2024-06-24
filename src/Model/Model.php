<?php

namespace TCGdex\Model;

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

    /**
     * @template T
     * @param T $model
     * @param object|null $data
     * @return T
     */
    public static function build($model, $data)
    {
        if (is_null($data)) {
            return null;
        }
        $model->fill($data);
        return $model;
    }

    /**
     * Basic implementation
     * ```
     * foreach ($data AS $key => $value) $this->{$key} = $value;
     * ```
     */
    protected function fill(object $data): void
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }
}

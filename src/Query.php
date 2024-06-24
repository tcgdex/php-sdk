<?php

namespace TCGdex;

class Query
{
    public static function create()
    {
        return new Query();
    }
    public array $params = [];


    public function includes(string $key, string $value)
    {
        return $this->equal($key, $value);
    }

    public function contains(string $key, string $value)
    {
        return $this->equal($key, $value);
    }

    public function equal(string $key, string $value)
    {
        $this->params[$key] = $value;

        return $this;
    }

    public function sort(string $key, string $order)
    {
        $this->params["sort:field"] = $key;
        $this->params["sort:order"] = $order;

        return $this;
    }

    public function paginate(int $page, int $itemsPerPage)
    {
        $this->params["pagination:page"] = $page;
        $this->params["pagination:itemsPerPage"] = $itemsPerPage;

        return $this;
    }
}

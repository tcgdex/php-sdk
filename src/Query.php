<?php

namespace TCGdex;

class Query
{
    public static function create(): Query
    {
        return new Query();
    }

    /**
     * @var Array<string|int, string|int> $params
     */
    public array $params = [];


    public function includes(string $key, string $value): self
    {
        return $this->equal($key, $value);
    }

    public function contains(string $key, string $value): self
    {
        return $this->equal($key, $value);
    }

    public function equal(string $key, string $value): self
    {
        $this->params[$key] = $value;

        return $this;
    }

    public function sort(string $key, string $order): self
    {
        $this->params["sort:field"] = $key;
        $this->params["sort:order"] = $order;

        return $this;
    }

    public function paginate(int $page, int $itemsPerPage): self
    {
        $this->params["pagination:page"] = $page;
        $this->params["pagination:itemsPerPage"] = $itemsPerPage;

        return $this;
    }
}

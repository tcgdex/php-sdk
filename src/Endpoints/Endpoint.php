<?php

namespace TCGdex\Endpoints;

use TCGdex\Model\Model;
use TCGdex\TCGdex;
use TCGdex\Query;

/**
 * @template Item
 * @template List
 */
class Endpoint
{
    /**
     * @param class-string<Item> $itemModel
     * @param class-string<List> $listModel
     */
    public function __construct(
        protected readonly TCGdex $tcgdex,
        protected readonly string $itemModel,
        protected readonly string|null $listModel,
        protected readonly string $endpoint
     ) {}

     /**
      * @return Item
      */
    public function get(string|int $id)
    {
        $res = $this->tcgdex->fetch($this->endpoint, $id);
        return Model::build(new $this->itemModel($this->tcgdex), $res);
    }

     /**
      * @return Array<List>
      */
    public function list(Query $query = null): array
    {
        $res = $this->tcgdex->fetchWithParams([$this->endpoint], is_null($query) ? null : $query->params);
        if (is_null($this->listModel)) {
            return $res;
        }
        $arr = array();
        foreach ($res as $item) {
            array_push($arr, Model::build(new $this->listModel($this->tcgdex), $item));
        }
        return $arr;
    }
}

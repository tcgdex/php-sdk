<?php

namespace TCGdex\Endpoints;

use TCGdex\Model\Card;
use TCGdex\Model\Model;
use TCGdex\Model\Set;
use TCGdex\Model\SetResume;
use TCGdex\TCGdex;

/**
 * @extends Endpoint<Set, SetResume>
 */
class SetEndpoint extends Endpoint
{
    public function __construct(
        TCGdex $tcgdex
    ) {
        parent::__construct(
            $tcgdex,
            Set::class,
            SetResume::class,
            'sets'
        );
    }

    public function getCard(string $set, string $localId): Card
    {
        $res = $this->tcgdex->fetch($this->endpoint, $set, $localId);
        return Model::build(new Card($this->tcgdex), $res);
    }
}

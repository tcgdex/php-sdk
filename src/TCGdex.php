<?php

namespace TCGdex;

use Buzz\Client\Curl;
use Exception;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;
use TCGdex\Model\Card;
use TCGdex\Model\CardResume;
use TCGdex\Model\Serie;
use TCGdex\Model\SerieResume;
use TCGdex\Model\StringEndpoint;
use TCGdex\Request;
use Composer\InstalledVersions;
use TCGdex\Endpoints\Endpoint;
use TCGdex\Endpoints\SetEndpoint;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 */
class TCGdex
{
    /**
     * @deprecated use `TCGdex::getVersion()` instead
     */
    public const VERSION = "2.x.x";

    /**
     * @return string
     */
    public static function getVersion()
    {
        try {
            return InstalledVersions::getVersion("tcgdex/sdk");
        } catch (Exception $e) {
            return "2.x.x";
        }
    }

    public const BASE_URI = "https://api.tcgdex.net/v2";

    /**
     * @var \Psr\SimpleCache\CacheInterface|null
     */
    public static $cache;

    /**
     * @var \Psr\Http\Message\ResponseFactoryInterface|null
     */
    public static $responseFactory;

    /**
     * @var \Psr\Http\Message\RequestFactoryInterface|null
     */
    public static $requestFactory;

    /**
     * @var \Psr\Http\Client\ClientInterface|null
     */
    public static $client;

    /**
     * @var int
     */
    public static $ttl = 60 * 1000 * 1000;

    /**
     * @var string
     * Possible values: en, fr
     */
    public $lang = "en";

    /**
     * @param String|null $lang
     */
    public function __construct($lang = null)
    {
        if (is_null(TCGdex::$cache)) {
            // no PSR16 implementation found, try loading the base one
            TCGdex::$cache = new Psr16Cache(new ArrayAdapter());
        }
        if (is_null(TCGdex::$responseFactory)) {
            // no PSR17 implementation found, try loading the base one
            TCGdex::$responseFactory = new Psr17Factory();
        }
        if (is_null(TCGdex::$requestFactory)) {
            // no PSR17 implementation found, try loading the base one
            TCGdex::$requestFactory = new Psr17Factory();
        }
        if (is_null(TCGdex::$client) && !is_null(TCGdex::$responseFactory)) {
            // no PSR18 implementation found, try loading the base one
            TCGdex::$client = new Curl(TCGdex::$responseFactory);
        }
        if (!is_null($lang)) {
            $this->lang = $lang;
        }

        // Setup the endpoints
        $this->card = new Endpoint($this, Card::class, CardResume::class, 'cards');
        $this->variant = new Endpoint($this, StringEndpoint::class, null, 'variants');
        $this->trainerType = new Endpoint($this, StringEndpoint::class, null, 'trainer-types');
        $this->suffix = new Endpoint($this, StringEndpoint::class, null, 'suffixes');
        $this->stage = new Endpoint($this, StringEndpoint::class, null, 'stages');
        $this->regulationMark = new Endpoint($this, StringEndpoint::class, null, 'regulation-marks');
        $this->energyType = new Endpoint($this, StringEndpoint::class, null, 'energy-types');
        $this->dexId = new Endpoint($this, StringEndpoint::class, null, 'dex-ids');
        $this->type = new Endpoint($this, StringEndpoint::class, null, 'types');
        $this->set = new SetEndpoint($this);
        $this->serie = new Endpoint($this, Serie::class, SerieResume::class, 'series');
        $this->retreat = new Endpoint($this, StringEndpoint::class, null, 'retreats');
        $this->rarity = new Endpoint($this, StringEndpoint::class, null, 'rarities');
        $this->illustrator = new Endpoint($this, StringEndpoint::class, null, 'illustrators');
        $this->hp = new Endpoint($this, StringEndpoint::class, null, 'hp');
        $this->category = new Endpoint($this, StringEndpoint::class, null, 'categories');
    }


    /**
     * The card endpoint of the TCGdex API
     * @var Endpoint<Card, CardResume> $card
     */
    public readonly Endpoint $card;

    /**
     * The variant endpoint of the TCGdex API
     * @var Endpoint<StringEndpoint, string> $variant
     */
    public readonly Endpoint $variant;

    /**
     * The trainerType endpoint of the TCGdex API
     * @var Endpoint<StringEndpoint, string> $trainerType
     */
    public readonly Endpoint $trainerType;

    /**
     * The suffix endpoint of the TCGdex API
     * @var Endpoint<StringEndpoint, string> $suffix
     */
    public readonly Endpoint $suffix;

    /**
     * The stage endpoint of the TCGdex API
     * @var Endpoint<StringEndpoint, string> $stage
     */
    public readonly Endpoint $stage;

    /**
     * The regulationMark endpoint of the TCGdex API
     * @var Endpoint<StringEndpoint, string> $regulationMark
     */
    public readonly Endpoint $regulationMark;

    /**
     * The energyType endpoint of the TCGdex API
     * @var Endpoint<StringEndpoint, string> $energyType
     */
    public readonly Endpoint $energyType;

    /**
     * The dexId endpoint of the TCGdex API
     * @var Endpoint<StringEndpoint, string> $dexId
     */
    public readonly Endpoint $dexId;

    /**
     * The type endpoint of the TCGdex API
     * @var Endpoint<StringEndpoint, string> $type
     */
    public readonly Endpoint $type;

    /**
     * The set endpoint of the TCGdex API
     */
    public readonly SetEndpoint $set;

    /**
     * The serie endpoint of the TCGdex API
     * @var Endpoint<Serie, SerieResume> $serie
     */
    public readonly Endpoint $serie;

    /**
     * The retreat endpoint of the TCGdex API
     * @var Endpoint<StringEndpoint, string> $retreat
     */
    public readonly Endpoint $retreat;

    /**
     * The rarity endpoint of the TCGdex API
     * @var Endpoint<StringEndpoint, string> $rarity
     */
    public readonly Endpoint $rarity;

    /**
     * The illustrator endpoint of the TCGdex API
     * @var Endpoint<StringEndpoint, string> $illustrator
     */
    public readonly Endpoint $illustrator;

    /**
     * The hp endpoint of the TCGdex API
     * @var Endpoint<StringEndpoint, string> $hp
     */
    public readonly Endpoint $hp;

    /**
     * The category endpoint of the TCGdex API
     * @var Endpoint<StringEndpoint, string> $category
     */
    public readonly Endpoint $category;

    /**
     * @param string|null $endpoint
     * @return mixed|null
     */
    public function fetch(...$endpoint)
    {
        return Request::fetch(TCGdex::BASE_URI, $this->lang, ...$endpoint);
    }

    /**
     * same as `$tcgdex->fetch` but it allows to add params to the URL
     * @param Array<string> $endpoint the endpoint paths as an array
     * @param array $params the list of parameters to add
     * @return mixed|null
     */
    public function fetchWithParams(array $endpoint, array $params = null)
    {
        return Request::fetchWithParams(
            Request::makePath(TCGdex::BASE_URI, $this->lang, ...$endpoint),
            $params
        );
    }

    /**
     * Fetch a card by its ID or local id if the set is named
     * @param String $id
     * @param String|null $set
     * @return Card|null
     * @deprecated 2.2.0 use `$this->set->getCard($set, $id);` or `$this->card->get($id);` instead.
     */
    public function fetchCard(string $id, ?string $set = null)
    {
        if (!is_null($set)) {
            return $this->set->getCard($set, $id);
        }
        return $this->card->get($id);
    }

    /**
     * @return CardResume[]|null
     * @deprecated 2.2.0 use `$tcgdex->card->list();` instead.
     */
    public function fetchCards()
    {
        return $this->card->list();
    }

    /**
     * @param string $category
     * @return StringEndpoint|null
     * @deprecated 2.2.0 use `$tcgdex->category->get($category);` instead.
     */
    public function fetchCategory(string $category)
    {
        return $this->category->get($category);
    }

    /**
     * @return string[]
     * @deprecated 2.2.0 use `$tcgdex->category->list();` instead.
     */
    public function fetchCategories()
    {
        return $this->category->list();
    }

    /**
     * @return StringEndpoint|null
     * @deprecated 2.2.0 use `$tcgdex->hp->get($hp);` instead.
     */
    public function fetchHp(string $hp)
    {
        return $this->hp->get($hp);
    }

    /**
     * @return string[]
     * @deprecated 2.2.0 use `$tcgdex->hp->list();` instead.
     */
    public function fetchHps()
    {
        return $this->hp->list();
    }


    /**
     * @return StringEndpoint|null
     * @deprecated 2.2.0 use `$tcgdex->illustrator->get($illustrator);` instead.
     */
    public function fetchIllustrator(string $illustrator)
    {
        return $this->illustrator->get($illustrator);
    }

    /**
     * @return string[]
     * @deprecated 2.2.0 use `$tcgdex->illustrator->list();` instead.
     */
    public function fetchIllustrators()
    {
        return $this->illustrator->list();
    }

    /**
     * @return StringEndpoint|null
     * @deprecated 2.2.0 use `$tcgdex->rarity->get($rarity);` instead.
     */
    public function fetchRarity(string $rarity)
    {
        return $this->rarity->get($rarity);
    }

    /**
     * @return string[]
     * @deprecated 2.2.0 use `$tcgdex->rarity->list();` instead.
     */
    public function fetchRarities()
    {
        return $this->rarity->list();
    }

    /**
     * @return StringEndpoint|null
     * @deprecated 2.2.0 use `$tcgdex->retreat->get($retreat);` instead.
     */
    public function fetchRetreat(string $retreat)
    {
        return $this->retreat->get($retreat);
    }

    /**
     * @return string[]
     * @deprecated 2.2.0 use `$tcgdex->retreat->list();` instead.
     */
    public function fetchRetreats()
    {
        return $this->retreat->list();
    }

    /**
     * @deprecated 2.2.0 use `$tcgdex->serie->get($serie);` instead.
     */
    public function fetchSerie(string $serie)
    {
        return $this->serie->get($serie);
    }

    /**
     * @deprecated 2.2.0 use `$tcgdex->serie->list();` instead.
     */
    public function fetchSeries()
    {
        return $this->serie->list();
    }

        /**
         * @deprecated 2.2.0 use `$tcgdext->get($set);` instead.
     */
    public function fetchSet(string $set)
    {
        return $this->set->get($set);
    }

    /**
     * @deprecated 2.2.0 use `$tcgdex->set->list();` instead.
     */
    public function fetchSets()
    {
        return $this->set->list();
    }

    /**
     * @return StringEndpoint|null
     * @deprecated 2.2.0 use `$tcgdex->type->get($type);` instead.
     */
    public function fetchType(string $type)
    {
        return $this->type->get($type);
    }

    /**
     * @return string[]
     * @deprecated 2.2.0 use `$tcgdex->type->list();` instead.
     */
    public function fetchTypes()
    {
        return $this->type->list();
    }

    /**
     * @return StringEndpoint|null
     * @deprecated 2.2.0 use `$tcgdex->dexId->get($dexId);` instead.
     */
    public function fetchDexId(string $dexId)
    {
        return $this->dexId->get($dexId);
    }

    /**
     * @return string[]
     * @deprecated 2.2.0 use `$tcgdex->dexId->list();` instead.
     */
    public function fetchDexIds()
    {
        return $this->dexId->list();
    }

    /**
     * @return StringEndpoint|null
     * @deprecated 2.2.0 use `$tcgdex->energyType->get($energyType);` instead.
     */
    public function fetchEnergyType(string $energyType)
    {
        return $this->energyType->get($energyType);
    }

    /**
     * @return string[]
     * @deprecated 2.2.0 use `$tcgdex->energyType->list();` instead.
     */
    public function fetchEnergyTypes()
    {
        return $this->energyType->list();
    }

    /**
     * @return StringEndpoint|null
     * @deprecated 2.2.0 use `$tcgdex->regulationMark->get($regulationMark);` instead.
     */
    public function fetchRegulationMark(string $regulationMark)
    {
        return $this->regulationMark->get($regulationMark);
    }

    /**
     * @return string[]
     * @deprecated 2.2.0 use `$tcgdex->regulationMark->list();` instead.
     */
    public function fetchRegulationMarks()
    {
        return $this->regulationMark->list();
    }

    /**
     * @return StringEndpoint|null
     * @deprecated 2.2.0 use `$tcgdex->stage->get($stage);` instead.
     */
    public function fetchStage(string $stage)
    {
        return $this->stage->get($stage);
    }

    /**
     * @return string[]
     * @deprecated 2.2.0 use `$tcgdex->stage->list();` instead.
     */
    public function fetchStages()
    {
        return $this->stage->list();
    }

    /**
     * @return StringEndpoint|null
     * @deprecated 2.2.0 use `$tcgdex->suffix->get($suffix);` instead.
     */
    public function fetchSuffix(string $suffix)
    {
        return $this->suffix->get($suffix);
    }

    /**
     * @return string[]
     * @deprecated 2.2.0 use `$tcgdex->suffix->list();` instead.
     */
    public function fetchSuffixes()
    {
        return $this->suffix->list();
    }

    /**
     * @return StringEndpoint|null
     * @deprecated 2.2.0 use `$tcgdex->trainerType->get($trainerType);` instead.
     */
    public function fetchTrainerType(string $trainerType)
    {
        return $this->trainerType->get($trainerType);
    }

    /**
     * @return string[]
     * @deprecated 2.2.0 use `$tcgdex->trainerType->list();` instead.
     */
    public function fetchTrainerTypes()
    {
        return $this->trainerType->list();
    }

    /**
     * @return StringEndpoint|null
     * @deprecated 2.2.0 use `$tcgdex->variant->get($variant);` instead.
     */
    public function fetchVariant(string $variant)
    {
        return $this->variant->get($variant);
    }

    /**
     * @return string[]
     * @deprecated 2.2.0 use `$tcgdex->variant->list();` instead.
     */
    public function fetchVariants()
    {
        return $this->variant->list();
    }
}

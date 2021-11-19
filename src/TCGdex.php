<?php

namespace TCGdex;

use Buzz\Client\Curl;
use Exception;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;
use TCGdex\Model\Card;
use TCGdex\Model\CardResume;
use TCGdex\Model\Model;
use TCGdex\Model\Serie;
use TCGdex\Model\SerieResume;
use TCGdex\Model\Set;
use TCGdex\Model\SetResume;
use TCGdex\Model\StringEndpoint;
use TCGdex\Request;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class TCGdex
{

    public const VERSION = "2.0.0";

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
        try {
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
        } catch (exception $e) {
            throw new Exception("something is missing in the setup, can't continue...");
        }
        if (!is_null($lang)) {
            $this->lang = $lang;
        }
    }

    /**
     * @param string|null $endpoint
     * @return mixed|null
     */
    public function fetch(...$endpoint)
    {
        return Request::fetch(TCGdex::BASE_URI, $this->lang, ...$endpoint);
    }

    /**
     * Fetch a card by its ID or local id if the set is named
     * @param String $id
     * @param String|null $set
     * @return Card|null
     */
    public function fetchCard(string $id, ?string $set = null)
    {
        if (is_null($set)) {
            $data = $this->fetch('cards', $id);
        } else {
            $data = $this->fetch('sets', $set, $id);
        }
        if (is_null($data)) {
            return null;
        }
        return Model::build(new Card($this), $data);
    }

    /**
     * @return CardResume[]|null
     */
    public function fetchCards()
    {
        $data = $this->fetch("cards");
        if (is_null($data)) {
            return null;
        }
        $arr = array();
        foreach ($data as $item) {
            array_push($arr, Model::build(new CardResume($this), $item));
        }
        return $arr;
    }

    /**
     * @param string $category
     * @return StringEndpoint|null
     */
    public function fetchCategory(string $category)
    {
        $data = $this->fetch("categories", $category);
        return Model::build(new StringEndpoint($this), $data);
    }

    /**
     * @return string[]
     */
    public function fetchCategories()
    {
        return $this->fetch("categories");
    }

    /**
     * @param string $hp
     * @return StringEndpoint|null
     */
    public function fetchHp(string $hp)
    {
        $data = $this->fetch("hp", $hp);
        return Model::build(new StringEndpoint($this), $data);
    }

    /**
     * @return string[]
     */
    public function fetchHps()
    {
        return $this->fetch("hp");
    }


    /**
     * @param string $illustrator
     * @return StringEndpoint|null
     */
    public function fetchIllustrator(string $illustrator)
    {
        $data = $this->fetch("illustrators", $illustrator);
        return Model::build(new StringEndpoint($this), $data);
    }

    /**
     * @return string[]
     */
    public function fetchIllustrators()
    {
        return $this->fetch("illustrators");
    }

    /**
     * @param string $rarity
     * @return StringEndpoint|null
     */
    public function fetchRarity(string $rarity)
    {
        $data = $this->fetch("rarities", $rarity);
        return Model::build(new StringEndpoint($this), $data);
    }

    /**
     * @return string[]
     */
    public function fetchRarities()
    {
        return $this->fetch("rarities");
    }

    /**
     * @param string $retreat
     * @return StringEndpoint|null
     */
    public function fetchRetreat(string $retreat)
    {
        $data = $this->fetch("retreats", $retreat);
        return Model::build(new StringEndpoint($this), $data);
    }

    /**
     * @return string[]
     */
    public function fetchRetreats()
    {
        return $this->fetch("retreats");
    }

    /**
     * @param string $serie
     * @return Serie|null
     */
    public function fetchSerie(string $serie)
    {
        $data = $this->fetch("series", $serie);
        return Model::build(new Serie($this), $data);
    }

    /**
     * @return SerieResume[]|null
     */
    public function fetchSeries()
    {
        $data = $this->fetch("series");
        if (is_null($data)) {
            return null;
        }
        $arr = array();
        foreach ($data as $item) {
            array_push($arr, Model::build(new SerieResume($this), $item));
        }
        return $arr;
    }

        /**
     * @param string $set
     * @return Set|null
     */
    public function fetchSet(string $set)
    {
        $data = $this->fetch("sets", $set);
        return Model::build(new Set($this), $data);
    }

    /**
     * @return SetResume[]|null
     */
    public function fetchSets()
    {
        $data = $this->fetch("sets");
        if (is_null($data)) {
            return null;
        }
        $arr = array();
        foreach ($data as $item) {
            array_push($arr, Model::build(new SetResume($this), $item));
        }
        return $arr;
    }

    /**
     * @param string $type
     * @return StringEndpoint|null
     */
    public function fetchType(string $type)
    {
        $data = $this->fetch("types", $type);
        return Model::build(new StringEndpoint($this), $data);
    }

    /**
     * @return string[]
     */
    public function fetchTypes()
    {
        return $this->fetch("types");
    }

    /**
     * @param string $dexId
     * @return StringEndpoint|null
     */
    public function fetchDexId(string $dexId)
    {
        $data = $this->fetch("dex-ids", $dexId);
        return Model::build(new StringEndpoint($this), $data);
    }

    /**
     * @return string[]
     */
    public function fetchDexIds()
    {
        return $this->fetch("dex-ids");
    }

    /**
     * @param string $energyType
     * @return StringEndpoint|null
     */
    public function fetchEnergyType(string $energyType)
    {
        $data = $this->fetch("energy-types", $energyType);
        return Model::build(new StringEndpoint($this), $data);
    }

    /**
     * @return string[]
     */
    public function fetchEnergyTypes()
    {
        return $this->fetch("energy-types");
    }

    /**
     * @param string $regulationMark
     * @return StringEndpoint|null
     */
    public function fetchRegulationMark(string $regulationMark)
    {
        $data = $this->fetch("regulation-marks", $regulationMark);
        return Model::build(new StringEndpoint($this), $data);
    }

    /**
     * @return string[]
     */
    public function fetchRegulationMarks()
    {
        return $this->fetch("regulation-marks");
    }

    /**
     * @param string $stage
     * @return StringEndpoint|null
     */
    public function fetchStage(string $stage)
    {
        $data = $this->fetch("stages", $stage);
        return Model::build(new StringEndpoint($this), $data);
    }

    /**
     * @return string[]
     */
    public function fetchStages()
    {
        return $this->fetch("stages");
    }

    /**
     * @param string $suffix
     * @return StringEndpoint|null
     */
    public function fetchSuffix(string $suffix)
    {
        $data = $this->fetch("suffixes", $suffix);
        return Model::build(new StringEndpoint($this), $data);
    }

    /**
     * @return string[]
     */
    public function fetchSuffixes()
    {
        return $this->fetch("suffixes");
    }

    /**
     * @param string $trainerType
     * @return StringEndpoint|null
     */
    public function fetchTrainerType(string $trainerType)
    {
        $data = $this->fetch("trainer-types", $trainerType);
        return Model::build(new StringEndpoint($this), $data);
    }

    /**
     * @return string[]
     */
    public function fetchTrainerTypes()
    {
        return $this->fetch("trainer-types");
    }

    /**
     * @param string $variant
     * @return StringEndpoint|null
     */
    public function fetchVariant(string $variant)
    {
        $data = $this->fetch("variants", $variant);
        return Model::build(new StringEndpoint($this), $data);
    }

    /**
     * @return string[]
     */
    public function fetchVariants()
    {
        return $this->fetch("variants");
    }
}

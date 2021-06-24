<?php

namespace TCGdex;

use Buzz\Client\Curl;
use Exception;
use Normalizer;
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

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class TCGdex
{

    public const VERSION = "2.0.0";

    public const BASE_URI = "https://api.tcgdex.net/v2";

    /**
     * @var \Psr\SimpleCache\CacheInterface
     */
    public static $cache;

    /**
     * @var \Psr\Http\Message\RequestFactoryInterface
     */
    public static $requestFactory;

    /**
     * @var \Psr\Http\Client\ClientInterface
     */
    public static $client;

    public static $ttl = 60 * 1000 * 1000;

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
            if (is_null(TCGdex::$requestFactory)) {
                // no PSR17 implementation found, try loading the base one
                TCGdex::$requestFactory = new Psr17Factory();
            }
            if (is_null(TCGdex::$client)) {
                // no PSR18 implementation found, try loading the base one
                TCGdex::$client = new Curl(TCGdex::$requestFactory);
            }
        } catch (exception $e) {
            throw new Exception("something is missing in the setup, can't continue...");
        }
        if (!is_null($lang)) {
            $this->lang = $lang;
        }
    }

    /**
     * @param String|null $endpoint
     * @return Object|null
     */
    public function fetch(...$endpoint)
    {
        // Fix and normalize the path as the compiler does
        // var_dump($endpoint);
        $filtered = array_filter($endpoint, function ($path) {
            return !is_null($path);
        });
        $fixedPath = array_map(function ($path) {
            /** @var string */
            $path = str_replace('?', '%3F', $path);
            // $normalized = Normalizer::normalize($path, Normalizer::NFC);
            $regexd = preg_replace("[\"'\x{0300}-\x{036f}]", '', $path);
            return urlencode(is_null($regexd) ? $path : $regexd);
        }, $filtered);
        $url = TCGdex::BASE_URI . '/' . $this->lang . '/' . implode('/', $fixedPath);
        var_dump($url);
        $cacheKey = $this->lang . implode('', $fixedPath);
        // fetch from the cache the response
        $response = TCGdex::$cache->get($cacheKey, null);
        if (is_null($response)) {
            // REquest remote if there is no hit in the cache
            $request = TCGdex::$requestFactory->createRequest('GET', $url);
            $request = $request->withAddedHeader("user-agent", "@tcgdex/php-sdk/" . TCGdex::VERSION);
            $response = TCGdex::$client->sendRequest($request);
            if ($response->getStatusCode() !== 200) {
                return null;
            }
            $result = json_decode(
                $response->getBody()->__toString()
            );
            // add the json to the cache
            TCGdex::$cache->set($cacheKey, $result, TCGdex::$ttl);
            return $result;
        }
        return $response;
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
     * @return SerieResume[]
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
     * @return SetResume[]
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
}

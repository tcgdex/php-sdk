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
use TCGdex\Model\StringEndpoint;

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
            $regexd = preg_replace("[\"'\u0300-\u036f]", '', $path);
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
     * @return string[]|StringEndpoint|null
     */
    public function fetchHp(?string $hp = null)
    {
        $data = $this->fetch("hp", $hp);
        if (!is_null($hp)) {
            return Model::build(new StringEndpoint($this), $data);
        }
        return $data;
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
}

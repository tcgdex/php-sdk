<?php

namespace TCGdex;

/**
 * Simple class that store the Request component
 */
class Request
{
    /**
     * @param string|null $path
     * @return mixed|null
     */
    public static function fetch(...$path)
    {
        $url = Request::makePath(...$path);

        return Request::fetchWithParams($url);
    }

    /**
     * @param string $url
     * @param Array<string, string> $params
     */
    public static function fetchWithParams(string $url, ?array $params = null): mixed
    {
        if (!is_null($params)) {
            $mapped = array_map(function (string $key, string $value) {
                return $key . '=' . $value;
            }, array_keys($params), array_values($params));
            $url = $url . "?" . implode('&', $mapped);
        }
        $cacheKey = preg_replace("/[\{\}\(\)\/\\\@\:]/", '', $url);

        // fetch from the cache the response
        $response = TCGdex::$cache->get($cacheKey, null);

        // Request is not cached
        if (is_null($response)) {
            // Request remote if there is no hit in the cache
            $request = TCGdex::$requestFactory->createRequest('GET', $url);

            // Add header for basic identification
            $request = $request->withAddedHeader("user-agent", "@tcgdex/php-sdk/" . TCGdex::getVersion());

            // Execute the request
            $response = TCGdex::$client->sendRequest($request);

            // if the request don't return content just return null
            if ($response->getStatusCode() !== 200) {
                return null;
            }

            // Decode the response
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
     * @param string|null $path
     */
    public static function makePath(...$path): string
    {
        // Filter null elements as they break the rest
        $filtered = array_filter($path, function ($path) {
            return !is_null($path);
        });

        // Fix the path by changing some elements
        $fixedPath = array_map(function ($path) {

            if (str_contains($path, "://")) {
                return $path;
            }

            // Replace ? with it's escped form
            /** @var string */
            $path = str_replace('?', '%3F', $path);

            // Remove special characters from the path
            $regexd = preg_replace("/[\"'\x{0300}-\x{036f}]/u", '', $path);

            // URL encode the path
            return urlencode(is_null($regexd) ? $path : $regexd);
        }, $filtered);

        // Build the url
        return implode('/', $fixedPath);
    }
}

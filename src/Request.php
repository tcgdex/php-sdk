<?php

namespace TCGdex;

/**
 * Simple class that store the Request component
 */
class Request
{
    public static function fetch(...$path)
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
        $url = implode('/', $fixedPath);
        $cacheKey = preg_replace("/[\{\}\(\)\/\\\@\:]/", '', $url);

        // fetch from the cache the response
        $response = TCGdex::$cache->get($cacheKey, null);

        // Request is not cached
        if (is_null($response)) {

            // Request remote if there is no hit in the cache
            $request = TCGdex::$requestFactory->createRequest('GET', $url);

            // Add header for basic identification
            $request = $request->withAddedHeader("user-agent", "@tcgdex/php-sdk/" . TCGdex::VERSION);

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
}

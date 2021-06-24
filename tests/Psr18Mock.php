<?php

namespace Tests;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Psr18Mock implements ClientInterface
{
    /**
     * @var string
     */
    private $response;

    /**
     * @var int
     */
    private $statusCode;

    public function __construct(string $response, int $statusCode = 200)
    {
        $this->response = $response;
        $this->statusCode = $statusCode;
    }
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $factory = new Psr17Factory();
        return $factory->createResponse($this->statusCode)->withBody($factory->createStream($this->response));
    }
}

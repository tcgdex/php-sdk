<?php declare(strict_types=1);

namespace Tests;

use TCGdex\TCGdex;
use Webclient\Fake\Client;
use Webclient\Fake\Handler\SimpleRoutingHandler;

use PHPUnit\Framework\TestCase;

final class StackTest extends TestCase
{

    public function __construct()
    {
        parent::__construct();
        $handler = new SimpleRoutingHandler($notFoundHandler);
        $handler
            ->route(['GET', 'HEAD'], '/entities/1', $entityHandler)
            ->route(['POST'], '/entities', $entityCreatedHandler)
            ->route(['DELETED'], '/entities/2', $entityDeletedHandler)
        ;
        $client = new Client($handler);
        TCGdex::$client = $client;
    }

    public function testCanInitLol(): void
    {
        TCGdex::$client =
        $tcgdex = new TCGdex("en");
        $card = $tcgdex->fetchCard('swsh1-1');
        $this->assertNotEmpty($card);
    }
}

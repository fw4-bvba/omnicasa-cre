<?php

/*
 * This file is part of the fw4/omnicasa-cre library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OmnicasaCRE\Tests;

use PHPUnit\Framework\TestCase;
use OmnicasaCRE\Omnicasa;
use OmnicasaCRE\ApiAdapter;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

abstract class ApiTestCase extends TestCase
{
    static protected $api;
    static protected $adapter;
    static protected $mockHandler;

    static public function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$mockHandler = new MockHandler();

        self::$adapter = new ApiAdapter([
            'handler' => new HandlerStack(self::$mockHandler)
        ]);
        self::$api = new Omnicasa('', null, [
            'handler' => new HandlerStack(self::$mockHandler)
        ]);
    }

    protected function queueResponse($response): void
    {
        if (is_array($response)) {
            $response = json_encode($response);
        }
        if (is_string($response)) {
            $response = new Response(200, [], $response);
        }

        self::$mockHandler->append($response);
    }
}

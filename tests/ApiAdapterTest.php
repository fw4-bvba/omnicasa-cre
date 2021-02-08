<?php

/*
 * This file is part of the fw4/omnicasa-cre library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OmnicasaCRE\Tests;

use OmnicasaCRE\ApiAdapter;
use OmnicasaCRE\Request\Request;
use OmnicasaCRE\Exception\ApiException;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use InvalidArgumentException;

class ApiAdapterTest extends ApiTestCase
{
    public function testConstructorHttpClient(): void
    {
        $client = new Client([
            'base_uri' => 'localhost'
        ]);
        $adapter = new ApiAdapter($client);

        $this->assertEquals('localhost', $adapter->getHttpClient()->getConfig('base_uri'));
    }

    public function testConstructorOptions(): void
    {
        $adapter = new ApiAdapter([
            'base_uri' => 'localhost'
        ]);

        $this->assertEquals('localhost', $adapter->getHttpClient()->getConfig('base_uri'));
    }

    public function testConstructorInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $adapter = new ApiAdapter(123);
    }

    public function testInvalidRequest(): void
    {
        $adapter = new ApiAdapter([
            'handler' => new HandlerStack(self::$mockHandler)
        ]);

        $this->queueResponse('{"Message":"Foo"}');
        $request = new Request('GET', 'endpoint');

        $this->expectException(ApiException::class);
        $adapter->request($request);
    }
}

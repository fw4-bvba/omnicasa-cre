<?php

/*
 * This file is part of the fw4/omnicasa-cre library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OmnicasaCRE\Tests\Request;

use OmnicasaCRE\Request\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testUri(): void
    {
        $request = new Request('GET', 'endpoint', ['foo' => 'bar']);
        $this->assertEquals('endpoint?foo=bar', strval($request->getUri()));
    }

    public function testDateTimeParameter(): void
    {
        $request = new Request('POST', 'endpoint', null, ['date' => new \DateTime('2021-01-01T00:00:00+00:00')]);
        $this->assertEquals('{"date":"2021-01-01T00:00:00+00:00"}', strval($request->getBody()));
    }

    public function testImplodeParameters(): void
    {
        $request = new Request('GET', 'endpoint', [
            'foo' => [1, 2, 3]
        ]);
        $this->assertEquals('endpoint?foo=1,2,3', strval($request->getUri()));
    }

    public function testSetParameter(): void
    {
        $request = new Request('GET', 'endpoint', [
            'foo' => [1, 2, 3]
        ]);
        $request->setParameter('foo', 'bar');
        $parameters = $request->getParameters();

        $this->assertEquals('bar', $parameters['foo']);
    }
}

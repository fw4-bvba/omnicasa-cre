<?php

/*
 * This file is part of the fw4/omnicasa-cre library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OmnicasaCRE\Tests\Response;

use OmnicasaCRE\Response\ResponseObject;
use OmnicasaCRE\Exception\InvalidDataException;
use OmnicasaCRE\Exception\InvalidPropertyException;
use PHPUnit\Framework\TestCase;

class ResponseObjectTest extends TestCase
{
    public function testDataTypes(): void
    {
        $response = new ResponseObject([
            'String' => 'foo',
            'Int' => 123,
            'Array' => [1, 2, 3],
            'Date' => '2020-01-01 00:00:00',
        ]);

        $this->assertIsString($response->string);
        $this->assertIsInt($response->int);
        $this->assertIsArray($response->array);
        $this->assertInstanceOf(\DateTime::class, $response->date);
    }

    public function testInvalidData(): void
    {
        $this->expectException(InvalidDataException::class);
        $response = new ResponseObject(123);
    }

    public function testMagicMethods(): void
    {
        $response = new ResponseObject([]);

        $response->Foo = 'bar';
        $this->assertTrue(isset($response->foo));
        $this->assertEquals('bar', $response->foo);

        unset($response->Foo);
        $this->assertFalse(isset($response->foo));
    }

    public function testInvalidProperty(): void
    {
        $response = new ResponseObject([]);

        $this->expectException(InvalidPropertyException::class);
        $foo = $response->bar;
    }

    public function testJsonEncoding(): void
    {
        $response = new ResponseObject([
            'String' => 'foo',
            'Int' => 123,
            'Array' => [1, 2, 3],
            'Date' => '2020-01-01 00:00:00',
        ]);

        $this->assertEquals('{"String":"foo","Int":123,"Array":[1,2,3],"Date":"2020-01-01 00:00:00"}', json_encode($response));
    }
}

<?php

/*
 * This file is part of the fw4/omnicasa-cre library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OmnicasaCRE\Tests\Response;

use OmnicasaCRE\Response\Response;
use OmnicasaCRE\Response\ResponseObject;
use OmnicasaCRE\Exception\InvalidDataException;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testValidData(): void
    {
        $response_data = new ResponseObject(json_decode('{"Value":{"Foo":"bar"}}', false));
        $response = new Response($response_data);

        $this->assertEquals('bar', $response->foo);
    }

    public function testInvalidData(): void
    {
        $response_data = new ResponseObject(json_decode('{"Foo":"bar"}', false));

        $this->expectException(InvalidDataException::class);
        $response = new Response($response_data);
    }
}

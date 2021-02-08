<?php

/*
 * This file is part of the fw4/omnicasa-cre library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OmnicasaCRE\Tests\Response;

use OmnicasaCRE\Request\CollectionRequest;
use OmnicasaCRE\Response\Collection;
use OmnicasaCRE\ApiAdapter;
use OmnicasaCRE\Tests\ApiTestCase;
use GuzzleHttp\HandlerStack;

class CollectionTest extends ApiTestCase
{
    public function testBuffer(): void
    {
        $this->queueResponse('{"Success":true,"Message":"","Value":{"Items":[{"Id":1},{"Id":2},{"Id":3}],"RowsCount":6,"From":1,"To":4}}');
        $this->queueResponse('{"Success":true,"Message":"","Value":{"Items":[{"Id":4},{"Id":5},{"Id":6}],"RowsCount":6,"From":4,"To":7}}');

        $request = new CollectionRequest('GET', 'endpoint');
        $request->setPage(0, 3);

        $keys = $ids = [];
        $response = new Collection($request, self::$adapter);
        foreach ($response as $key => $item) {
            $keys[] = $key;
            $ids[] = $item->id;
        }

        $this->assertEquals([0,1,2,3,4,5], $keys);
        $this->assertEquals([1,2,3,4,5,6], $ids);
    }

    public function testOffsets(): void
    {
        $this->queueResponse('{"Success":true,"Message":"","Value":{"Items":[{"Id":1},{"Id":2},{"Id":3}],"RowsCount":3,"From":1,"To":4}}');
        $request = new CollectionRequest('GET', 'endpoint');
        $response = new Collection($request, self::$adapter);

        $this->assertTrue(isset($response[2]));
        $this->assertFalse(isset($response[3]));
        $this->assertFalse(isset($response['foo']));

        $this->expectError();
        $item = $response[3];
    }
}

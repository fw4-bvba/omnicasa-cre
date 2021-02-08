<?php

/*
 * This file is part of the fw4/omnicasa-cre library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OmnicasaCRE\Tests\Response;

use OmnicasaCRE\Response\CollectionPage;
use OmnicasaCRE\Response\ResponseObject;
use PHPUnit\Framework\TestCase;

class CollectionPageTest extends TestCase
{
    public function testGet(): void
    {
        $response = new ResponseObject(json_decode('{"Success":true,"Message":"","Value":{"Items":[{"Id":1},{"Id":2},{"Id":3}],"RowsCount":6,"From":1,"To":3}}', false));
        $page = new CollectionPage($response);

        $this->assertEquals(2, $page->get(1)->id);
    }

    public function testGetPage(): void
    {
        $response = new ResponseObject(json_decode('{"Success":true,"Message":"","Value":{"Items":[{"Id":1},{"Id":2},{"Id":3}],"RowsCount":6,"From":1,"To":3}}', false));
        $page = new CollectionPage($response);

        $this->assertEquals(0, $page->getPage());
    }

    public function testGetPageSize(): void
    {
        $response = new ResponseObject(json_decode('{"Success":true,"Message":"","Value":{"Items":[{"Id":1},{"Id":2},{"Id":3}],"RowsCount":6,"From":1,"To":3}}', false));
        $page = new CollectionPage($response);

        $this->assertEquals(3, $page->getPageSize());
    }

    public function testGetTotalCount(): void
    {
        $response = new ResponseObject(json_decode('{"Success":true,"Message":"","Value":{"Items":[{"Id":1},{"Id":2},{"Id":3}],"RowsCount":6,"From":1,"To":3}}', false));
        $page = new CollectionPage($response);

        $this->assertEquals(6, $page->getTotalCount());
    }

    public function testGetPageCount(): void
    {
        $response = new ResponseObject(json_decode('{"Success":true,"Message":"","Value":{"Items":[{"Id":1},{"Id":2},{"Id":3}],"RowsCount":6,"From":1,"To":3}}', false));
        $page = new CollectionPage($response);

        $this->assertEquals(2, $page->getPageCount());
    }

    public function testCount(): void
    {
        $response = new ResponseObject(json_decode('{"Success":true,"Message":"","Value":{"Items":[{"Id":1},{"Id":2},{"Id":3}],"RowsCount":6,"From":1,"To":3}}', false));
        $page = new CollectionPage($response);

        $this->assertEquals(3, count($page));
    }
}

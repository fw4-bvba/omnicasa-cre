<?php

/*
 * This file is part of the fw4/omnicasa-cre library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OmnicasaCRE\Tests\Request;

use OmnicasaCRE\Request\CollectionRequest;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class CollectionRequestTest extends TestCase
{
    public function testSetPage(): void
    {
        $request = new CollectionRequest('GET', 'endpoint');
        $request->setPage(3, 24);
        $this->assertEquals(3, $request->getPage());
        $this->assertEquals(24, $request->getPageSize());
    }

    public function testSetPageInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $request = new CollectionRequest('GET', 'endpoint');
        $request->setPage(-1);
    }

    public function testPaginationParameters(): void
    {
        $request = new CollectionRequest('GET', 'endpoint');
        $request->setPage(3, 24);
        $this->assertEquals([
            'Limit1' => 73,
            'Limit2' => 96
        ], $request->getParameters());
    }
}

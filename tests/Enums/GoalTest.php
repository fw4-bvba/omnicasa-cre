<?php

/*
 * This file is part of the fw4/omnicasa-cre library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OmnicasaCRE\Tests\Enums;

use OmnicasaCRE\Enums\Goal;
use PHPUnit\Framework\TestCase;

class GoalTest extends TestCase
{
    public function testAll(): void
    {
        $this->assertEquals([
            'ForSale' => 0,
            'ForRent' => 1,
        ], Goal::all());
    }
}

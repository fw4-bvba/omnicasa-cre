<?php

/*
 * This file is part of the fw4/omnicasa-cre library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OmnicasaCRE\Response;

class CollectionIterator implements \Iterator
{
    /** @var Collection */
    protected $collection;

    /** @var int */
    protected $position = 0;

    public function __construct(CollectionBase $collection)
    {
        $this->collection = $collection;
    }

    /* Iterator implementation */

    public function current()
    {
        return $this->collection->get($this->position);
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next()
    {
        $this->position++;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return $this->collection->offsetExists($this->position);
    }
}

<?php

/*
 * This file is part of the fw4/omnicasa-cre library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OmnicasaCRE\Response;

abstract class CollectionBase implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * Get the item at a specific index.
     *
     * @param int $position
     *
     * @return mixed
     */
    abstract public function get(int $position);

    /* IteratorAggregate implementation */

    public function getIterator(): CollectionIterator
    {
        return new CollectionIterator($this);
    }

    /* ArrayAccess implementation */

    public function offsetExists($offset): bool
    {
        if (!is_int($offset)) {
            return false;
        }
        return $offset >= 0 && $offset < $this->count();
    }

    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            trigger_error('Undefined offset: ' . $offset);
        }
        return $this->get($offset);
    }

    /**
     * @codeCoverageIgnore
     */
    public function offsetSet($offset, $value): void
    {
        throw new \Exception('offsetSet not implemented on CollectionBase');
    }

    /**
     * @codeCoverageIgnore
     */
    public function offsetUnset($offset): void
    {
        throw new \Exception('offsetUnset not implemented on CollectionBase');
    }
}

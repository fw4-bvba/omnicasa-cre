<?php

/*
 * This file is part of the fw4/omnicasa-cre library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OmnicasaCRE\Response;

class CollectionPage extends CollectionBase
{
    /** @var array */
    protected $data;

    /** @var int */
    protected $page;

    /** @var int */
    protected $pageSize;

    /** @var int */
    protected $totalCount;

    public function __construct(ResponseObject $data)
    {
        $this->data = $data->Value->Items ?? [];
        $this->pageSize = $data->Value->To - $data->Value->From + 1;
        $this->page = floor($data->Value->From / $this->pageSize);
        $this->totalCount = $data->Value->RowsCount;
    }

    /**
     * {@inheritdoc}
     */
    public function get(int $position)
    {
        return $this->data[$position];
    }

    /**
     * Get the index of this page.
     *
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * Get the maximum amount of items requested for this page.
     *
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * Get the total amount of items across all pages.
     *
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * Get the total amount of pages.
     *
     * @return int
     */
    public function getPageCount(): int
    {
        return ceil($this->getTotalCount() / $this->getPageSize());
    }

    /**
     * @codeCoverageIgnore
     */
    public function __debugInfo(): array
    {
        return $this->data;
    }

    /* Countable implementation */

    public function count(): int
    {
        return count($this->data);
    }
}

<?php

/*
 * This file is part of the fw4/omnicasa-cre library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OmnicasaCRE\Request;

use OmnicasaCRE\Omnicasa;
use InvalidArgumentException;

class CollectionRequest extends Request
{
    /** @var int */
    protected $page = 0;

    /** @var int */
    protected $pageSize;

    public function __construct(
        string $method,
        string $endpoint,
        ?array $parameters = null,
        ?array $body = null,
        array $headers = [],
        int $page = 0
    ) {
        $this->page = $page;
        if (is_null($parameters)) {
            $parameters = [];
        }
        $parameters = array_merge($parameters, $this->getPaginationParameters());
        parent::__construct($method, $endpoint, $parameters, $body, $headers);
    }

    /**
     * Set the page to be retrieved, starting at 0.
     *
     * @param int $page
     * @param int|null $page_size Integer, or null to use default
     *
     * @return self
     */
    public function setPage(int $page, ?int $page_size = null): self
    {
        if ($page < 0) {
            throw new InvalidArgumentException('Page index must be 0 or greater');
        }
        $this->page = $page;

        if ($page_size) {
            $this->pageSize = $page_size;
        }

        $this->setParameters($this->getPaginationParameters());
        return $this;
    }

    /**
     * Get the page to be retrieved, starting at 0.
     *
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * Get the amount of items to be retrieved per page.
     *
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize ?? Omnicasa::getDefaultPageSize();
    }

    /**
     * Get the query parameters representing the current page.
     *
     * @return array
     */
    protected function getPaginationParameters(): array
    {
        return [
           'Limit1' => $this->getPage() * $this->getPageSize() + 1,
           'Limit2' => ($this->getPage() + 1) * $this->getPageSize(),
        ];
    }
}

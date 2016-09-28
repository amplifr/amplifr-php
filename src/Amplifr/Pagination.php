<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr;

/**
 * Class Pagination
 * @package Amplifr
 */
class Pagination implements PaginationInterface
{
    /**
     * @var \SplObjectStorage
     */
    protected $items;

    /**
     * @var int
     */
    protected $currentPage;

    /**
     * @var int
     */
    protected $totalPages;

    /**
     * Pagination constructor.
     * @param \SplObjectStorage $itemsStorage
     * @param $currentPage
     * @param $totalPages
     */
    public function __construct(\SplObjectStorage $itemsStorage, $currentPage, $totalPages)
    {
        $this->setItems($itemsStorage);
        $this->setCurrentPage($currentPage);
        $this->setTotalPages($totalPages);
    }

    /**
     * @return \SplObjectStorage
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param \SplObjectStorage $items
     */
    protected function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @param int $currentPage
     */
    protected function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
    }

    /**
     * @return int
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * @param int $totalPages
     */
    protected function setTotalPages($totalPages)
    {
        $this->totalPages = $totalPages;
    }
}
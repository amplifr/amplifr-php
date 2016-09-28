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
interface PaginationInterface
{
    /**
     * get items
     *
     * @return \SplObjectStorage
     */
    public function getItems();

    /**
     * get current page number
     *
     * @return int
     */
    public function getCurrentPage();

    /**
     * get total pages
     *
     * @return int
     */
    public function getTotalPages();
}
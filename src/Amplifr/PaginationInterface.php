<?php
/**
 * Created by PhpStorm.
 * User: Mesilov
 * Date: 29.09.2016
 * Time: 0:33
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
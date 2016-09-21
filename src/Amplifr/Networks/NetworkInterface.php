<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Networks;


/**
 * Interface NetworkInterface
 * @package Amplifr\Networks
 */
interface NetworkInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getCode();

    /**
     * @return string
     */
    public function getUserName();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return int
     */
    public function getSubscribersCount();

    /**
     * @return int
     */
    public function getLikesCount();

    /**
     * @return int
     */
    public function getSharesCount();

    /**
     * @return int
     */
    public function getCommentsCount();

    /**
     * @return int
     */
    public function getLinkClicksCount();

    /**
     * @return int
     */
    public function getSubscribersDiffCount();

    /**
     * @return int|null
     */
    public function getUniqueViewsCount();

    /**
     * @return int|null
     */
    public function getFanUniqueViewsCount();

    /**
     * @return int|null
     */
    public function getTotalViewsCount();
}
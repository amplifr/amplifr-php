<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Networks;


use Amplifr\Stat\CounterInterface;

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
    public function getSubscribersDiffCount();

    /**
     * @return CounterInterface
     */
    public function getStatistics();
}
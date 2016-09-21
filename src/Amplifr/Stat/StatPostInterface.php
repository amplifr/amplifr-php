<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Stat;


/**
 * Class StatPost
 * @package Amplifr\Posts
 */
interface StatPostInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getNetworkCode();

    /**
     * @return string
     */
    public function getUserName();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return CounterInterface
     */
    public function getStatistics();
}
<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Stat;


/**
 * Class StatPublication
 * @package Amplifr\Stat
 */
interface StatPublicationInterface
{
    /**
     * @return string
     */
    public function getPreface();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return CounterInterface
     */
    public function getTotalStatistics();

    /**
     * @return int
     */
    public function getPublicationId();

    /**
     * @return \SplObjectStorage
     */
    public function getStatPosts();
}
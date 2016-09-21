<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Stat;


/**
 * Class Counter
 * @package Amplifr\Stat
 */
interface CounterInterface
{
    /**
     * @return int
     */
    public function getLikes();

    /**
     * @return int
     */
    public function getShares();

    /**
     * @return int
     */
    public function getComments();

    /**
     * @return int
     */
    public function getLinkClicks();

    /**
     * @return int
     */
    public function getVideoPlays();

    /**
     * @return int
     */
    public function getUniqueViews();

    /**
     * @return int
     */
    public function getFanUniqueViews();

    /**
     * @return int
     */
    public function getTotalViews();
}
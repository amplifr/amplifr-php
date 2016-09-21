<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Stat;


/**
 * Interface StatReportInterface
 * @package Amplifr\Stat
 */
interface StatReportInterface
{
    /**
     * @return \DateTime
     */
    public function getDateFrom();

    /**
     * @return \DateTime
     */
    public function getDateTo();

    /**
     * @return \SplObjectStorage
     */
    public function getNetworks();

    /**
     * @return mixed
     */
    public function getBestPublications();

    /**
     * @return mixed
     */
    public function getInteractions();
}
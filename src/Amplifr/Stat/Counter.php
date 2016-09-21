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
class Counter implements CounterInterface
{
    /**
     * @var int | null
     */
    protected $likes;

    /**
     * @var int | null
     */
    protected $shares;

    /**
     * @var int | null
     */
    protected $comments;

    /**
     * @var int | null
     */
    protected $linkClicks;

    /**
     * @var int | null
     */
    protected $videoPlays;

    /**
     * @var int | null
     */
    protected $uniqueViews;

    /**
     * @var int | null
     */
    protected $fanUniqueViews;

    /**
     * @var int | null
     */
    protected $totalViews;

    /**
     * @return int
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param int $likes
     */
    protected function setLikes($likes)
    {
        $this->likes = $likes;
    }

    /**
     * @return int
     */
    public function getShares()
    {
        return $this->shares;
    }

    /**
     * @param int $shares
     */
    protected function setShares($shares)
    {
        $this->shares = $shares;
    }

    /**
     * @return int
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param int $comments
     */
    protected function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return int
     */
    public function getLinkClicks()
    {
        return $this->linkClicks;
    }

    /**
     * @param int $linkClicks
     */
    protected function setLinkClicks($linkClicks)
    {
        $this->linkClicks = $linkClicks;
    }

    /**
     * @return int
     */
    public function getVideoPlays()
    {
        return $this->videoPlays;
    }

    /**
     * @param int $videoPlays
     */
    protected function setVideoPlays($videoPlays)
    {
        $this->videoPlays = $videoPlays;
    }

    /**
     * @return int
     */
    public function getUniqueViews()
    {
        return $this->uniqueViews;
    }

    /**
     * @param int $uniqueViews
     */
    protected function setUniqueViews($uniqueViews)
    {
        $this->uniqueViews = $uniqueViews;
    }

    /**
     * @return int
     */
    public function getFanUniqueViews()
    {
        return $this->fanUniqueViews;
    }

    /**
     * @param int $fanUniqueViews
     */
    protected function setFanUniqueViews($fanUniqueViews)
    {
        $this->fanUniqueViews = $fanUniqueViews;
    }

    /**
     * @return int
     */
    public function getTotalViews()
    {
        return $this->totalViews;
    }

    /**
     * @param int $totalViews
     */
    protected function setTotalViews($totalViews)
    {
        $this->totalViews = $totalViews;
    }

    /**
     * Counter constructor.
     * @param $arItem
     */
    public function __construct($arItem)
    {
        $this->setLikes($arItem['likes']);
        $this->setShares($arItem['shares']);
        $this->setComments($arItem['comments']);
        $this->setLinkClicks($arItem['linkClicks']);
        $this->setVideoPlays($arItem['videoPlays']);
        $this->setUniqueViews($arItem['uniqueViews']);
        $this->setFanUniqueViews($arItem['fanUniqueViews']);
        $this->setTotalViews($arItem['totalViews']);
    }
}
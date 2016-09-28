<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Posts;


use Amplifr\Stat\CounterInterface;

/**
 * Class Post
 * @package Amplifr\Posts
 */
interface PostInterface
{
    /**
     * @return \SplObjectStorage
     */
    public function getSubforms();

    /**
     * @return boolean
     */
    public function isClickCounting();

    /**
     * @return CounterInterface
     */
    public function getStatistics();

    /**
     * @return int
     */
    public function getId();

    /**
     * @return array
     */
    public function getUrl();

    /**
     * @return \DateTime
     */
    public function getTime();

    /**
     * @return string
     */
    public function getText();

    /**
     * @return int
     */
    public function getAuthorId();

    /**
     * @return array
     */
    public function getErrors();

    /**
     * @return array
     */
    public function getStates();

    /**
     * @return array
     */
    public function getSocialNetworks();

    /**
     * @return array
     */
    public function getPublications();

    /**
     * @return array
     */
    public function getAttachmentsId();

    /**
     * @return array
     */
    public function getPreviews();
}
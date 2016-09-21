<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Stat;


/**
 * Class Post
 * @package Amplifr\Posts
 */
class StatPost implements StatPostInterface
{
    /**
     * @var array
     */
    protected $arPost;
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $networkCode;

    /**
     * @var string
     */
    protected $userName;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var CounterInterface
     */
    protected $statistics;

    /**
     * Post constructor.
     * @param $arPostItem
     */
    public function __construct($arPostItem)
    {
        $this->arPost = $arPostItem;
        $this->setId($arPostItem['id']);
        $this->setUserName($arPostItem['name']);
        $this->setUrl($arPostItem['url']);
        $this->setNetworkCode($arPostItem['network']);
        $this->setStatistics(new Counter($arPostItem['stats']));
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    protected function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNetworkCode()
    {
        return $this->networkCode;
    }

    /**
     * @param string $networkCode
     */
    protected function setNetworkCode($networkCode)
    {
        $this->networkCode = $networkCode;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    protected function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    protected function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return CounterInterface
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * @param CounterInterface $obStatistics
     */
    protected function setStatistics(CounterInterface $obStatistics)
    {
        $this->statistics = $obStatistics;
    }
}
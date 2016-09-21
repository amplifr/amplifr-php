<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Networks;


use Amplifr\Stat\CounterInterface;
use Amplifr\Stat\Counter;

/**
 * Class Network
 * @package Amplifr\Networks
 */
class Network implements NetworkInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var array
     */
    protected $arNetwork;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $userName;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var int
     */
    protected $subscribersCount;

    /**
     * @var int
     */
    protected $subscribersDiffCount;

    /**
     * @var CounterInterface
     */
    protected $statistics;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    protected function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    protected function setCode($code)
    {
        $this->code = $code;
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
     * @return int
     */
    public function getSubscribersCount()
    {
        return $this->subscribersCount;
    }

    /**
     * @param int $subscribersCount
     */
    protected function setSubscribersCount($subscribersCount)
    {
        $this->subscribersCount = $subscribersCount;
    }

    /**
     * @return int
     */
    public function getSubscribersDiffCount()
    {
        return $this->subscribersDiffCount;
    }

    /**
     * @param int $subscribersDiffCount
     */
    protected function setSubscribersDiffCount($subscribersDiffCount)
    {
        $this->subscribersDiffCount = $subscribersDiffCount;
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

    /**
     * Network constructor.
     * @param $arNetworkAccount
     */
    public function __construct($arNetworkAccount)
    {
        $this->arNetwork = $arNetworkAccount;
        $this->setId($arNetworkAccount['id']);
        $this->setCode($arNetworkAccount['network']);
        $this->setUserName($arNetworkAccount['name']);
        $this->setUrl($arNetworkAccount['url']);
        $this->setSubscribersCount((int)$arNetworkAccount['subscribers']);
        $this->setSubscribersDiffCount((int)$arNetworkAccount['subscribersDiff']);
        $obStat = new Counter($arNetworkAccount['stats']);
        $this->setStatistics($obStat);
    }
}
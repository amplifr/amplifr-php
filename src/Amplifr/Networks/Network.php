<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Networks;


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
     * @var int | null
     */
    protected $likesCount;

    /**
     * @var int | null
     */
    protected $commentsCount;

    /**
     * @var int | null
     */
    protected $sharesCount;

    /**
     * @var int | null
     */
    protected $linkClicksCount;

    /**
     * @var int | null
     */
    protected $uniqueViewsCount;

    /**
     * @var int | null
     */
    protected $fanUniqueViewsCount;

    /**
     * @var int | null
     */
    protected $totalViewsCount;

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
     * @return int|null
     */
    public function getLikesCount()
    {
        return $this->likesCount;
    }

    /**
     * @param int|null $likesCount
     */
    protected function setLikesCount($likesCount)
    {
        $this->likesCount = $likesCount;
    }

    /**
     * @return int|null
     */
    public function getCommentsCount()
    {
        return $this->commentsCount;
    }

    /**
     * @param int|null $commentsCount
     */
    protected function setCommentsCount($commentsCount)
    {
        $this->commentsCount = $commentsCount;
    }

    /**
     * @return int|null
     */
    public function getSharesCount()
    {
        return $this->sharesCount;
    }

    /**
     * @param int|null $sharesCount
     */
    protected function setSharesCount($sharesCount)
    {
        $this->sharesCount = $sharesCount;
    }

    /**
     * @return int|null
     */
    public function getLinkClicksCount()
    {
        return $this->linkClicksCount;
    }

    /**
     * @param int|null $linkClicksCount
     */
    protected function setLinkClicksCount($linkClicksCount)
    {
        $this->linkClicksCount = $linkClicksCount;
    }

    /**
     * @return int|null
     */
    public function getUniqueViewsCount()
    {
        return $this->uniqueViewsCount;
    }

    /**
     * @param int|null $uniqueViewsCount
     */
    protected function setUniqueViewsCount($uniqueViewsCount)
    {
        $this->uniqueViewsCount = $uniqueViewsCount;
    }

    /**
     * @return int|null
     */
    public function getFanUniqueViewsCount()
    {
        return $this->fanUniqueViewsCount;
    }

    /**
     * @param int|null $fanUniqueViewsCount
     */
    protected function setFanUniqueViewsCount($fanUniqueViewsCount)
    {
        $this->fanUniqueViewsCount = $fanUniqueViewsCount;
    }

    /**
     * @return int|null
     */
    public function getTotalViewsCount()
    {
        return $this->totalViewsCount;
    }

    /**
     * @param int|null $totalViewsCount
     */
    protected function setTotalViewsCount($totalViewsCount)
    {
        $this->totalViewsCount = $totalViewsCount;
    }

    /**
     * Network constructor.
     * @param $arNetworkAccount
     * @todo перейти на счётчики
     */
    public function __construct($arNetworkAccount)
    {
        $this->arNetwork = $arNetworkAccount;
        $this->setId($arNetworkAccount['id']);
        $this->setCode($arNetworkAccount['network']);
        $this->setUserName($arNetworkAccount['name']);
        $this->setUrl($arNetworkAccount['url']);
        $this->setSubscribersCount($arNetworkAccount['subscribers']);
        $this->setSubscribersDiffCount($arNetworkAccount['subscribersDiff']);
        $this->setLikesCount($arNetworkAccount['stats']['likes']);
        $this->setSharesCount($arNetworkAccount['stats']['shares']);
        $this->setCommentsCount($arNetworkAccount['stats']['comments']);
        $this->setLinkClicksCount($arNetworkAccount['stats']['linkClicks']);
        $this->setUniqueViewsCount($arNetworkAccount['stats']['uniqueViews']);
        $this->setFanUniqueViewsCount($arNetworkAccount['stats']['fanUniqueViews']);
        $this->setTotalViewsCount($arNetworkAccount['stats']['totalViews']);
    }
}
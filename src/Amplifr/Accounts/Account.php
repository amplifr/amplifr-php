<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Accounts;


/**
 * Class Account
 * @package Amplifr\Accounts
 */
class Account implements AccountInterface
{
    /**
     * @var array
     */
    protected $arAccount;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $avatar;

    /**
     * @var string
     */
    protected $network;

    /**
     * @var string
     */
    protected $networkAbbreviation;

    /**
     * @var boolean
     */
    protected $isActive;

    /**
     * @var boolean
     */
    protected $isPublishable;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    protected function setName($name)
    {
        $this->name = $name;
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
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     */
    protected function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return string
     */
    public function getNetwork()
    {
        return $this->network;
    }

    /**
     * @param string $network
     */
    protected function setNetwork($network)
    {
        $this->network = $network;
    }

    /**
     * @return string
     */
    public function getNetworkAbbreviation()
    {
        return $this->networkAbbreviation;
    }

    /**
     * @param string $networkAbbreviation
     */
    protected function setNetworkAbbreviation($networkAbbreviation)
    {
        $this->networkAbbreviation = $networkAbbreviation;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isActive
     */
    protected function setActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return boolean
     */
    public function isPublishable()
    {
        return $this->isPublishable;
    }

    /**
     * @param boolean $isPublishable
     */
    protected function setPublishable($isPublishable)
    {
        $this->isPublishable = $isPublishable;
    }

    /**
     * Account constructor.
     * @param $arSocialAccount
     */
    public function __construct($arSocialAccount)
    {
        $this->arAccount = $arSocialAccount;
        $this->setId($arSocialAccount['id']);
        $this->setName($arSocialAccount['name']);
        $this->setUrl($arSocialAccount['url']);
        $this->setAvatar($arSocialAccount['avatar']);
        $this->setNetwork($arSocialAccount['network']);
        $this->setNetworkAbbreviation($arSocialAccount['networkAbbr']);
        $this->setActive($arSocialAccount['active']);
        $this->setPublishable($arSocialAccount['publishable']);
    }
}
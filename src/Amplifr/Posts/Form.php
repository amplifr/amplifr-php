<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Posts;


/**
 * Class Form
 * @package Amplifr\Posts
 */
class Form implements FormInterface
{
    /**
     * @var array
     */
    protected $arForm;

    /**
     * @var array
     */
    protected $arSocialNetworks;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var array
     */
    protected $url;

    /**
     * @var array
     */
    protected $arPreviews;

    /**
     * @var array
     */
    protected $arAttachments;

    /**
     * Form constructor.
     * @param $arFormItem
     */
    public function __construct($arFormItem)
    {
        $this->arForm = $arFormItem;
        $this->setSocialNetworks($arFormItem['socials']);
        $this->setText($arFormItem['text']);
        $this->setUrl($arFormItem['url']);
        $this->setPreviews($arFormItem['previews']);
        $this->setAttachmentsId($arFormItem['attachments']);
    }

    /**
     * @return array
     */
    public function getSocialNetworks()
    {
        return $this->arSocialNetworks;
    }

    /**
     * @param array $arSocialNetworks
     */
    protected function setSocialNetworks(array $arSocialNetworks = array())
    {
        $this->arSocialNetworks = $arSocialNetworks;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    protected function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return array
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
        $this->url = (string)$url;
    }

    /**
     * @return array
     */
    public function getPreviews()
    {
        return $this->arPreviews;
    }

    /**
     * @param array $arPreviews
     */
    protected function setPreviews(array $arPreviews = array())
    {
        $this->arPreviews = $arPreviews;
    }

    /**
     * @return array
     */
    public function getAttachmentsId()
    {
        return $this->arAttachments;
    }

    /**
     * @param array $arAttachments
     */
    protected function setAttachmentsId(array $arAttachments = array())
    {
        $this->arAttachments = $arAttachments;
    }
}
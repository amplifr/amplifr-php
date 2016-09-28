<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Posts;


use Amplifr\Attachments\AttachmentInterface;
use Amplifr\Accounts\AccountInterface;

/**
 * Class Draft
 * @package Amplifr\Posts
 */
class Draft implements DraftInterface
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $cardUrl;

    /**
     * @var \DateTime
     */
    protected $publicationDateTime;

    /**
     * @var \SplObjectStorage
     */
    protected $attachments;

    /**
     * @var \SplObjectStorage
     */
    protected $socialNetworks;

    /**
     * @var \SplObjectStorage
     */
    protected $subforms;

    /**
     * Draft constructor.
     * @param $text
     * @param $cardUrl
     * @param \DateTime $publicationDateTime
     */
    public function __construct($text, $cardUrl, \DateTime $publicationDateTime)
    {
        $this->setText($text);
        $this->setCardUrl($cardUrl);
        $this->setPublicationDateTime($publicationDateTime);
        $this->attachments = new \SplObjectStorage();
        $this->socialNetworks = new \SplObjectStorage();
        $this->subforms = new \SplObjectStorage();
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @param $cardUrl
     */
    public function setCardUrl($cardUrl)
    {
        $this->cardUrl = $cardUrl;
    }

    /**
     * @param \DateTime $publicationDateTime
     */
    public function setPublicationDateTime(\DateTime $publicationDateTime)
    {
        $this->publicationDateTime = $publicationDateTime;
    }

    /**
     * @param AttachmentInterface $obAttachment
     * @todo move to \SplObjectStorage
     */
    public function addAttachment(AttachmentInterface $obAttachment)
    {
        $this->attachments->attach($obAttachment);
    }

    /**
     * @param \SplObjectStorage $obSocialNetworkAccountsStorage
     */
    public function setSocialNetworkAccounts(\SplObjectStorage $obSocialNetworkAccountsStorage)
    {
        $this->socialNetworks = $obSocialNetworkAccountsStorage;
    }

    /**
     * @param DraftInterface $obSubform
     * @todo move to \SplObjectStorage
     */
    public function addSubform(DraftInterface $obSubform)
    {
        $this->subforms->attach($obSubform);
    }

    /**
     * @return array
     */
    protected function getAttachments()
    {
        $arResult = array();
        foreach ($this->attachments as $cnt => $attachmentItem) {
            /**
             * @var $attachmentItem AttachmentInterface
             */
            $arResult[] = sprintf('%s:%d', $attachmentItem->getType(), $attachmentItem->getId());
        }
        return $arResult;
    }

    /**
     * @return array
     */
    protected function getSocialNetworks()
    {
        $arResult = array();
        $this->socialNetworks->rewind();
        foreach ($this->socialNetworks as $cnt => $accountItem) {
            /**
             * @var $accountItem AccountInterface
             */
            $arResult[] = $accountItem->getId();
        }
        return $arResult;
    }

    /**
     * @return array
     */
    protected function getSubforms()
    {
        $arResult = array();
        $this->subforms->rewind();
        foreach ($this->subforms as $cnt => $subformItem) {
            /**
             * @var DraftInterface $subformItem
             */
            $arItemSubform = $subformItem->getData();
            unset($arItemSubform['time']);
            $arResult[] = $arItemSubform;
        }
        return $arResult;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return array(
            'text' => $this->text,
            'url' => $this->cardUrl,
            'time' => $this->publicationDateTime->format(DATE_ISO8601),
            'attachments' => $this->getAttachments(),
            'socials' => $this->getSocialNetworks(),
            'subforms' => $this->getSubforms()
        );
    }
}
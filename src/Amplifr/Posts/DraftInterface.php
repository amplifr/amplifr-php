<?php

namespace Amplifr\Posts;

use Amplifr\Accounts\AccountInterface;
use Amplifr\Attachments\AttachmentInterface;
use Amplifr\Networks\NetworkInterface;


/**
 * Class Draft
 * @package Amplifr\Posts
 */
interface DraftInterface
{
    /**
     * @param $text
     */
    public function setText($text);

    /**
     * @param $cardUrl
     */
    public function setCardUrl($cardUrl);

    /**
     * @param \DateTime $publicationDateTime
     */
    public function setPublicationDateTime(\DateTime $publicationDateTime);

    /**
     * @param AttachmentInterface $obAttachment
     * @todo move to \SplObjectStorage
     */
    public function addAttachment(AttachmentInterface $obAttachment);

    /**
     * @param \SplObjectStorage $obSocialNetworkAccountsStorage
     */
    public function setSocialNetworkAccounts(\SplObjectStorage $obSocialNetworkAccountsStorage);

    /**
     * @param DraftInterface $obSubform
     * @todo move to \SplObjectStorage
     */
    public function addSubform(DraftInterface $obSubform);

    /**
     * @return array
     */
    public function getData();
}
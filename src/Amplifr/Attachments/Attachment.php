<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Attachments;

/**
 * Class Attachment
 * @package Amplifr\Attachments
 */
abstract class Attachment implements AttachmentInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $url;

    /**
     * Image constructor.
     * @param $imageId
     * @param $imageUrl
     */
    public function __construct($imageId, $imageUrl)
    {
        $this->setId($imageId);
        $this->setUrl($imageUrl);
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
     * @param $imageId
     */
    protected function setId($imageId)
    {
        $this->id = (int)$imageId;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
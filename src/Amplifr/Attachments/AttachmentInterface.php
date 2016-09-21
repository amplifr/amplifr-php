<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Attachments;


/**
 * Class Image
 * @package Amplifr\Attachments
 */
interface AttachmentInterface
{
    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getType();
}
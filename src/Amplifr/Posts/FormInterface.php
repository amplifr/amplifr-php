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
interface FormInterface
{
    /**
     * @return array
     */
    public function getSocialNetworks();

    /**
     * @return string
     */
    public function getText();

    /**
     * @return array
     */
    public function getUrl();

    /**
     * @return array
     */
    public function getPreviews();

    /**
     * @return array
     */
    public function getAttachmentsId();
}
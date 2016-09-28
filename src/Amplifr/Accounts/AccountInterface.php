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
interface AccountInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return string
     */
    public function getAvatar();

    /**
     * @return string
     */
    public function getNetwork();

    /**
     * @return string
     */
    public function getNetworkAbbreviation();

    /**
     * @return boolean
     */
    public function isActive();

    /**
     * @return boolean
     */
    public function isPublishable();
}
<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Users;


/**
 * Interface UserInterface
 * @package Amplifr\Users
 */
interface UserInterface
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
    public function getEmail();

    /**
     * @return boolean
     */
    public function isConfirmed();

    /**
     * @return \DateTimeZone
     */
    public function getTimeZone();

    /**
     * @return int
     */
    public function getTimeZoneUtcOffset();

    /**
     * @return string
     */
    public function getRole();
}
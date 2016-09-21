<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Projects;


/**
 * Class Project
 * @package Amplifr\Projects
 */
interface ProjectInterface
{
    /**
     * @return mixed
     */
    public function getName();

    /**
     * @return int
     */
    public function getId();

    /**
     * @return array
     */
    public function getBestPublicationTime();

    /**
     * @return \SplObjectStorage of Account
     */
    public function getSocialAccounts();

    /**
     * @return \SplObjectStorage of User
     */
    public function getUsers();
}
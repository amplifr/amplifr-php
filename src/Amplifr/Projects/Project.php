<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Projects;


use Amplifr\Accounts\Account;
use Amplifr\Users\User;

/**
 * Class Project
 * @package Amplifr\Projects
 */
class Project implements ProjectInterface
{
    /**
     * @var array
     */
    protected $arProject;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $arBestPublicationTime;

    /**
     * Project constructor.
     * @param array $arProject
     */
    public function __construct(array $arProject)
    {
        $this->arProject = $arProject;
        $this->setId($this->arProject['id']);
        $this->setName($this->arProject['name']);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    protected function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param $projectId
     */
    protected function setId($projectId)
    {
        $this->id = (int)$projectId;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $arBestPublicationTime
     */
    protected function setBestPublicationTime($arBestPublicationTime)
    {
        $this->arBestPublicationTime = $arBestPublicationTime;
    }

    /**
     * @return array
     */
    public function getBestPublicationTime()
    {
        return $this->arBestPublicationTime;
    }

    /**
     * @return \SplObjectStorage of Account
     */
    public function getSocialAccounts()
    {
        $obCollection = new \SplObjectStorage();
        foreach ($this->arProject['socialAccounts'] as $cnt => $arItemAccount) {
            $obCollection->attach(new Account($arItemAccount));
        }

        return $obCollection;
    }

    /**
     * @return \SplObjectStorage of User
     */
    public function getUsers()
    {
        $obCollection = new \SplObjectStorage();
        foreach ($this->arProject['users'] as $cnt => $arItemUser) {
            $obCollection->attach(new User($arItemUser));
        }

        return $obCollection;
    }
}
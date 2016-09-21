<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Users;


/**
 * Class Users
 * @package Amplifr\Users
 */
class User implements UserInterface
{
    /**
     * @var array
     */
    protected $arUser;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var boolean
     */
    protected $isConfirmed;

    /**
     * @var \DateTimeZone
     */
    protected $timeZone;

    /**
     * @var int
     */
    protected $timeZoneUtcOffset;

    /**
     * @var string
     */
    protected $role;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    protected function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    protected function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    protected function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return boolean
     */
    public function isConfirmed()
    {
        return $this->isConfirmed;
    }

    /**
     * @param boolean $isConfirmed
     */
    protected function setIsConfirmed($isConfirmed)
    {
        $this->isConfirmed = $isConfirmed;
    }

    /**
     * @return \DateTimeZone
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }

    /**
     * @param string $timeZone
     */
    protected function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;
    }

    /**
     * @return int
     */
    public function getTimeZoneUtcOffset()
    {
        return $this->timeZoneUtcOffset;
    }

    /**
     * @param int $timeZoneUtcOffset
     */
    protected function setTimeZoneUtcOffset($timeZoneUtcOffset)
    {
        $this->timeZoneUtcOffset = $timeZoneUtcOffset;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    protected function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * User constructor.
     * @param $arUser
     */
    public function __construct($arUser)
    {
        $this->arUser = $arUser;
        $this->setId($arUser['id']);
        $this->setName($arUser['name']);
        $this->setEmail($arUser['email']);
        $this->setIsConfirmed($arUser['confirmed']);
        $this->setTimeZone(new \DateTimeZone($arUser['timezone']));
        $this->setTimeZoneUtcOffset($arUser['tzUtcOffset']);
        $this->setRole($arUser['role']);
    }
}
<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Users;


use Amplifr\Users\UserInterface;

/**
 * Class UserTest
 * @package Amplifr\Users
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers       UserInterface::getId()
     * @dataProvider validUserProvider
     */
    public function testGetId($arUser)
    {
        $obUser = new User($arUser);
        $this->assertInternalType("int", $obUser->getId());
    }

    /**
     * @covers       UserInterface::getName()
     * @dataProvider validUserProvider
     */
    public function testGetName($arUser)
    {
        $obUser = new User($arUser);
        $this->assertInternalType("string", $obUser->getName());
    }

    /**
     * @covers       UserInterface::getEmail()
     * @dataProvider validUserProvider
     */
    public function testGetEmail($arUser)
    {
        $obUser = new User($arUser);
        $this->assertInternalType("string", $obUser->getEmail());
    }

    /**
     * @covers       UserInterface::isConfirmed()
     * @dataProvider validUserProvider
     */
    public function testIsConfirmed($arUser)
    {
        $obUser = new User($arUser);
        $this->assertInternalType("boolean", $obUser->isConfirmed());
    }

    /**
     * @covers       UserInterface::getTimeZone()
     * @dataProvider validUserProvider
     */
    public function testGetTimeZone($arUser)
    {
        $obUser = new User($arUser);
        $this->assertInstanceOf('DateTimeZone', $obUser->getTimeZone());
    }

    /**
     * @covers       UserInterface::getTimeZoneUtcOffset()
     * @dataProvider validUserProvider
     */
    public function testGetTimeZoneUtcOffset($arUser)
    {
        $obUser = new User($arUser);
        $this->assertInternalType("int", $obUser->getTimeZoneUtcOffset());
    }

    /**
     * @covers       UserInterface::getRole()
     * @dataProvider validUserProvider
     */
    public function testGetRole($arUser)
    {
        $obUser = new User($arUser);
        $this->assertInternalType('string', $obUser->getRole());
    }

    /**
     * @return array
     */
    public function validUserProvider()
    {
        return array(
            "valid user #1" =>
                array(
                    array(
                        "id" => 1,
                        "role" => "admin",
                        "name" => "Alexey Gaziev",
                        "email" => "email@example.com",
                        "confirmed" => true,
                        "timezone" => "Europe/Moscow",
                        "tzUtcOffset" => 3
                    )
                ),
            "valid user #2" => array(
                array(
                    "id" => 2,
                    "role" => "user",
                    "name" => "Alexey ivanov",
                    "email" => "email@example.com",
                    "confirmed" => false,
                    "timezone" => "Europe/Moscow",
                    "tzUtcOffset" => 3
                )
            )
        );
    }
}
<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


$loader = require __DIR__ . "/../vendor/autoload.php";
$loader->addPsr4('Amplifr\\', __DIR__ . '/Amplifr');

date_default_timezone_set('UTC');
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
class Image extends Attachment
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'image';
    }
}
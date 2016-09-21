<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Attachments;


/**
 * Class Video
 * @package Amplifr\Attachments
 */
class Video extends Attachment
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'video';
    }
}
<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Stat;


/**
 * Class StatPublication
 * @package Amplifr\Stat
 */
class StatPublication implements StatPublicationInterface
{
    /**
     * @var array
     */
    protected $arStatPublication;

    /**
     * @var int
     */
    protected $publicationId;

    /**
     * @var string
     */
    protected $preface;

    /**
     * @var CounterInterface
     */
    protected $totalStatistics;

    /**
     * @var string
     */
    protected $amplifrAdminUrl;

    /**
     * @var \SplObjectStorage
     */
    protected $statPosts;

    /**
     * StatPublication constructor.
     * @param $arPublicationStatItem
     */
    public function __construct($arPublicationStatItem)
    {
        $this->arStatPublication = $arPublicationStatItem;

        $this->setPublicationId($arPublicationStatItem['id']);
        $this->setPreface($arPublicationStatItem['preface']);
        $this->setUrl($arPublicationStatItem['pubs'][-1]['url']);

        // total stat
        $obTotalStatCounter = new Counter($arPublicationStatItem['pubs'][-1]['stats']);
        $this->setTotalStatistics($obTotalStatCounter);

        // stat per posts
        $obStatPosts = new \SplObjectStorage();
        foreach ($arPublicationStatItem['pubs'] as $amplifrPostId => $arPostItem) {
            if ('total' === $arPostItem['network']) {
                continue;
            }
            $arPostItem['id'] = $amplifrPostId;
            $obStatPosts->attach(new StatPost($arPostItem));
        }
        $this->setStatPosts($obStatPosts);
    }

    /**
     * @param \SplObjectStorage $obStatPosts
     */
    protected function setStatPosts(\SplObjectStorage $obStatPosts)
    {
        $this->statPosts = $obStatPosts;
    }

    /**
     * @return \SplObjectStorage
     */
    public function getStatPosts()
    {
        return $this->statPosts;
    }

    /**
     * @param CounterInterface $obCounter
     */
    protected function setTotalStatistics(CounterInterface $obCounter)
    {
        $this->totalStatistics = $obCounter;
    }

    /**
     * @return int
     */
    public function getPublicationId()
    {
        return $this->publicationId;
    }

    /**
     * @param $publicationId
     */
    protected function setPublicationId($publicationId)
    {
        $this->publicationId = (int)$publicationId;
    }

    /**
     * @param $prefaceText
     */
    protected function setPreface($prefaceText)
    {
        $this->preface = $prefaceText;
    }

    /**
     * @return string
     */
    public function getPreface()
    {
        return $this->preface;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->amplifrAdminUrl;
    }

    /**
     * @param $amplifrAdminUrl string
     */
    protected function setUrl($amplifrAdminUrl)
    {
        $this->amplifrAdminUrl = $amplifrAdminUrl;
    }

    /**
     * @return CounterInterface
     */
    public function getTotalStatistics()
    {
        return $this->totalStatistics;
    }
}
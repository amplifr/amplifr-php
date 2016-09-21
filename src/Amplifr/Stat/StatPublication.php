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
        $this->setTotalStatistics($arPublicationStatItem['pubs'][-1]['stats']);
        $this->setStatPosts($arPublicationStatItem['pubs']);
    }

    /**
     * @param $arPostItems array
     * @todo перенести в конструктор
     */
    protected function setStatPosts($arPostItems)
    {
        $this->statPosts = new \SplObjectStorage();
        foreach ($arPostItems as $amplifrPostId => $arPostItem) {
            if ('total' === $arPostItem['network']) {
                continue;
            }
            $arPostItem['id'] = $amplifrPostId;
            $this->statPosts->attach(new StatPost($arPostItem));
        }
    }

    /**
     * @return \SplObjectStorage
     */
    public function getStatPosts()
    {
        return $this->statPosts;
    }

    /**
     * @param $arStatItem
     */
    protected function setTotalStatistics($arStatItem)
    {
        $this->totalStatistics = new Counter($arStatItem);
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
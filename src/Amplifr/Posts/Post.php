<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Posts;


use Amplifr\Stat\CounterInterface;
use Amplifr\Stat\Counter;

/**
 * Class Post
 * @package Amplifr\Posts
 */
class Post implements PostInterface
{

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var \SplObjectStorage
     */
    protected $subforms;

    /**
     * @var array
     */
    protected $arPost;

    /**
     * @var int post id
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $time;

    /**
     * @var int
     */
    protected $authorId;

    /**
     * @var CounterInterface
     */
    protected $statistics;

    /**
     * @var array
     */
    protected $arErrors;

    /**
     * @var array
     */
    protected $arStates;

    /**
     * @var array
     */
    protected $arPublications;

    /**
     * @var boolean
     */
    protected $isClickCounting;

    /**
     * Post constructor.
     * @param $arPostItem
     */
    public function __construct($arPostItem)
    {
        $this->arPost = $arPostItem;

        $this->form = new Form($arPostItem);

        $this->setId($arPostItem['id']);
        $this->setAuthorId($arPostItem['author']);
        $this->setErrors($arPostItem['errors']);
        $this->setStates($arPostItem['states']);

        $this->setPublications($arPostItem['publications']);
        $this->setIsClickCounting($arPostItem['clickCounting']);

        $subforms = new \SplObjectStorage();
        foreach ($arPostItem['subforms'] as $cnt => $arFormItem) {
            $subforms->attach(new Form($arFormItem));
        }
        $this->setSubForms($subforms);

        $arPostItem['linkClicks'] = $arPostItem['clicks'];
        $obStat = new Counter($arPostItem);
        $this->setStatistics($obStat);

        $obPostTime = new \DateTime();
        $obPostTime->setTimestamp($arPostItem['time']);
        $this->setTime($obPostTime);
    }

    /**
     * @param \SplObjectStorage $subforms
     */
    protected function setSubForms(\SplObjectStorage $subforms)
    {
        $this->subforms = $subforms;
    }

    /**
     * @return \SplObjectStorage
     */
    public function getSubforms()
    {
        return $this->subforms;
    }

    /**
     * @return boolean
     */
    public function isClickCounting()
    {
        return $this->isClickCounting;
    }

    /**
     * @param boolean $isClickCounting
     */
    protected function setIsClickCounting($isClickCounting)
    {
        $this->isClickCounting = (boolean)$isClickCounting;
    }

    /**
     * @param CounterInterface $obStatistics
     */
    protected function setStatistics(CounterInterface $obStatistics)
    {
        $this->statistics = $obStatistics;
    }

    /**
     * @return CounterInterface
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

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
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getUrl()
    {
        return $this->form->getUrl();
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }


    /**
     * @param \DateTime $postTimeCreate
     */
    protected function setTime(\DateTime $postTimeCreate)
    {
        $this->time = $postTimeCreate;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->form->getText();
    }

    /**
     * @return int
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * @param int $authorId
     */
    protected function setAuthorId($authorId)
    {
        $this->authorId = (int)$authorId;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->arErrors;
    }

    /**
     * @param array $arErrors
     */
    protected function setErrors(array $arErrors = array())
    {
        $this->arErrors = $arErrors;
    }

    /**
     * @return array
     */
    public function getStates()
    {
        return $this->arStates;
    }

    /**
     * @param array $arStates
     */
    protected function setStates(array $arStates = array())
    {
        $this->arStates = $arStates;
    }

    /**
     * @return array
     */
    public function getSocialNetworks()
    {
        return $this->form->getSocialNetworks();
    }

    /**
     * @return array
     */
    public function getPublications()
    {
        return $this->arPublications;
    }

    /**
     * @param array $arPublications
     */
    protected function setPublications(array $arPublications = array())
    {
        $this->arPublications = $arPublications;
    }

    /**
     * @return array
     */
    public function getAttachmentsId()
    {
        return $this->form->getAttachmentsId();
    }

    /**
     * @return array
     */
    public function getPreviews()
    {
        return $this->form->getPreviews();
    }
}
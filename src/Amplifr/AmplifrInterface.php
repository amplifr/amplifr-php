<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr;


use Amplifr\Exceptions\AmplifrException;
use Amplifr\Stat\StatReportInterface;
use Amplifr\Posts\DraftInterface;

/**
 * Class Amplifr
 * @package Amplifr
 */
interface AmplifrInterface
{
    /**
     * get projects
     *
     * @return \SplObjectStorage of ProjectInterface
     *
     * @throws AmplifrException
     */
    public function getProjects();

    /**
     * get amplifr accounts by project id
     *
     * @param $projectId
     *
     * @return \SplObjectStorage of AccountInterface
     *
     * @throws AmplifrException
     */
    public function getAccounts($projectId);

    /**
     * get users
     *
     * @param int $projectId
     *
     * @return \SplObjectStorage of UserInterface
     *
     * @throws AmplifrException
     */
    public function getUsers($projectId);

    /**
     * get statistic report by period
     *
     * @param int $projectId
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     *
     * @return StatReportInterface
     *
     * @throws AmplifrException
     */
    public function getStatReport($projectId, \DateTime $dateFrom, \DateTime $dateTo);

    /**
     * get statistic report by publication id
     *
     * @param int $projectId
     * @param int $amplifrPublicationId
     *
     * @return \SplObjectStorage of StatPublication
     *
     * @throws AmplifrException
     */
    public function getStatByPublicationId($projectId, $amplifrPublicationId);

    /**
     * get statistic report by URL
     *
     * @param int $projectId
     * @param string $publicationUrl
     *
     * @throws AmplifrException
     *
     * @return  \SplObjectStorage of StatPublication
     */
    public function getStatByPublicationUrl($projectId, $publicationUrl);

    /**
     * delete post
     *
     * @param int $projectId
     * @param int $postId
     *
     * @throws AmplifrException
     *
     * @return array
     */
    public function deletePost($projectId, $postId);

    /**
     * get information about post
     *
     * @param $projectId
     * @param $postId
     *
     * @throws AmplifrException
     *
     * @return \SplObjectStorage of Post
     */
    public function getPost($projectId, $postId);

    /**
     * add new post to Amplifr
     *
     * @param int $projectId
     * @param DraftInterface $obNewPost
     *
     * @throws AmplifrException
     *
     * @return \SplObjectStorage of Post
     */
    public function addNewPost($projectId, DraftInterface $obNewPost);
}
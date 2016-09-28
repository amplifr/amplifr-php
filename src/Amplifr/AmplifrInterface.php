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
use Amplifr\Stat\StatPublicationInterface;

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
     * get accounts
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
     * @return \SplObjectStorage
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
     * @return  \SplObjectStorage
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
}
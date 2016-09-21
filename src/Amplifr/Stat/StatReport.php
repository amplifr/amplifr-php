<?php

/*
 * This file is part of the Amplifr package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amplifr\Stat;


use Amplifr\Networks\Network;

/**
 * Class StatReport
 * @package Amplifr\Stat
 */
class StatReport implements StatReportInterface
{
    /**
     * @var array
     */
    protected $arStatReport;

    /**
     * @var \DateTime
     */
    protected $dateFrom;

    /**
     * @var \DateTime
     */
    protected $dateTo;

    /**
     * @var \SplObjectStorage
     */
    protected $networks;

    /**
     * @return \DateTime
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * @param \DateTime $dateFrom
     */
    protected function setDateFrom($dateFrom)
    {
        $this->dateFrom = $dateFrom;
    }

    /**
     * @return \DateTime
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * @param \DateTime $dateTo
     */
    protected function setDateTo($dateTo)
    {
        $this->dateTo = $dateTo;
    }

    /**
     * @param $networkObjectsStorage \SplObjectStorage
     */
    protected function setNetworks($networkObjectsStorage)
    {
        $this->networks = $networkObjectsStorage;
    }

    /**
     * @return \SplObjectStorage
     */
    public function getNetworks()
    {
        return $this->networks;
    }

    /**
     * @return mixed
     * @todo migrate to objects
     */
    public function getBestPublications()
    {
        return $this->arStatReport['bestPubs'];
    }

    /**
     * @return mixed
     * @todo migrate to objects
     */
    public function getInteractions()
    {
        return $this->arStatReport['interactions'];
    }

    /**
     * StatReport constructor.
     * @param $arStatReport
     */
    public function __construct($arStatReport)
    {
        $this->arStatReport = $arStatReport;
        $this->setDateFrom(new \DateTime($arStatReport['from']));
        $this->setDateTo(new \DateTime($arStatReport['to']));

        $networkObjects = new \SplObjectStorage();
        foreach ($arStatReport['networks'] as $networkId => $arItemNetwork) {
            $arItemNetwork['id'] = $networkId;
            $networkObjects->attach(new Network($arItemNetwork));
        }
        $this->setNetworks($networkObjects);
    }
}
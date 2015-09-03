<?php

namespace BB\Domain\Infrastructure;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class DeviceCost
{

    /**
     * @ORM\Column(type="boolean", name="requires_induction")
     */
    private $requiresInduction;

    /**
     * @ORM\Column(type="string")
     */
    private $inductionCategory;

    /**
     * @ORM\Column(type="integer")
     */
    private $accessFee;

    /**
     * @ORM\Column(type="integer")
     */
    private $usageCost;

    /**
     * @ORM\Column(type="string")
     */
    private $usageCostPeriod;

    /**
     * Cost constructor.
     *
     * @param $requiresInduction
     * @param $inductionCategory
     * @param $accessFee
     * @param $usageCost
     * @param $usageCostPeriod
     */
    public function __construct($requiresInduction, $inductionCategory, $accessFee, $usageCost, $usageCostPeriod)
    {
        $this->requiresInduction = $requiresInduction;
        $this->inductionCategory = $inductionCategory;
        $this->accessFee         = $accessFee;
        $this->usageCost         = $usageCost;
        $this->usageCostPeriod   = $usageCostPeriod;
    }


}
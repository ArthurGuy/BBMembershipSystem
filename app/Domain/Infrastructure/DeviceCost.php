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
     * Cost constructor.
     *
     * @param $requiresInduction
     * @param $inductionCategory
     * @param $accessFee
     * @param $usageCost
     * @param $usageCostPer
     */
    public function __construct($requiresInduction, $inductionCategory, $accessFee, $usageCost, $usageCostPer)
    {
        $this->requiresInduction = $requiresInduction;
        $this->inductionCategory = $inductionCategory;
        $this->accessFee         = $accessFee;
        $this->usageCost         = $usageCost;
        $this->usageCostPer   = $usageCostPer;
    }

    /**
     * @return mixed
     */
    public function getRequiresInduction()
    {
        return $this->requiresInduction;
    }

    /**
     * @return mixed
     */
    public function getAccessFee()
    {
        return $this->accessFee;
    }

    /**
     * @return mixed
     */
    public function getUsageCost()
    {
        return $this->usageCost;
    }

    /**
     * @ORM\Column(type="string")
     */
    private $usageCostPer;


}
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
    private $usageCostPer;

    /**
     * Cost constructor.
     *
     * @param bool   $requiresInduction
     * @param string $inductionCategory
     * @param int    $accessFee
     * @param int    $usageCost Cost in pence
     * @param string $usageCostPer
     */
    public function __construct($requiresInduction, $inductionCategory, $accessFee, $usageCost, $usageCostPer)
    {
        $this->requiresInduction = $requiresInduction;
        $this->inductionCategory = $inductionCategory;
        $this->accessFee         = $accessFee;
        $this->usageCost         = $usageCost;
        $this->usageCostPer      = $usageCostPer;
    }

    /**
     * @return bool
     */
    public function requiresInduction()
    {
        return $this->requiresInduction;
    }

    /**
     * @return mixed
     */
    public function accessFee()
    {
        return $this->accessFee;
    }

    /**
     * @return mixed
     */
    public function usageCost()
    {
        return $this->usageCost;
    }

    /**
     * @return bool
     */
    public function hasUsageCharge()
    {
        return ($this->usageCost > 0);
    }

    /**
     * @return string
     */
    public function usageCostPer()
    {
        return $this->usageCostPer;
    }

    /**
     * @return mixed
     */
    public function inductionCategory()
    {
        return $this->inductionCategory;
    }
}
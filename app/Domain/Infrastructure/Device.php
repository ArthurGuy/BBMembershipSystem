<?php

namespace BB\Domain\Infrastructure;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Device
 *
 * @ORM\Entity(repositoryClass="\BB\Domain\Infrastructure\DeviceRepository")
 * @ORM\Table(name="equipment")

 */
class Device
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\JoinColumn(name="room", referencedColumnName="`key`")
     * @ORM\ManyToOne(targetEntity="\BB\Domain\Infrastructure\Room", inversedBy="equipment")
     */
    protected $room;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=50, columnName="`key`")
     */
    protected $key;

    /**
     * @ORM\Embedded(class="DeviceCost", columnPrefix=false)
     */
    protected $cost;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $working;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $permaloan;

    /**
     * Device constructor.
     */
    public function __construct($name, $key)
    {
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @return mixed
     */
    public function getWorking()
    {
        return $this->working;
    }

    /**
     * @return mixed
     */
    public function getPermaloan()
    {
        return $this->permaloan;
    }

}
<?php

namespace BB\Domain\Infrastructure;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Device
 *
 * @ORM\Entity()
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
     * @ORM\JoinColumn(name="room", referencedColumnName="key")
     * @ORM\ManyToOne(targetEntity="\BB\Domain\Infrastructure\Room", inversedBy="equipment")
     */
    protected $room;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $key;

    /**
     * @ORM\Embedded(class="DeviceCost")
     */
    protected $cost;

    /**
     * Device constructor.
     */
    public function __construct($name, $key)
    {
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

}
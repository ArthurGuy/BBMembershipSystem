<?php

namespace BB\Domain\Infrastructure;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Room
 *
 * @ORM\Entity(repositoryClass="\BB\Domain\Infrastructure\RoomRepository")
 *
 */
class Room
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=50, name="`key`")
     */
    private $key;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $shortDescription;

    /**
     * @ORM\Column(type="json_array")
     */
    private $ppe;

    /**
     * @ORM\OneToMany(targetEntity="\BB\Domain\Infrastructure\Device", cascade={"persist"}, mappedBy="room")
     */
    private $devices;

    /**
     * Room constructor.
     *
     * @param $key
     * @param $name
     */
    public function __construct($key, $name, $shortDescription, $ppe)
    {
        $this->key  = $key;
        $this->name = $name;
        $this->shortDescription = $shortDescription;
        $this->ppe = $ppe;
        $this->devices = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Device[]
     */
    public function getEquipment()
    {
        return $this->devices->toArray();
    }
}
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
     * @ORM\Column(type="string", length=50)
     */
    protected $id;

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
     * @param $id
     * @param $name
     */
    public function __construct($id, $name, $shortDescription, $ppe)
    {
        $this->id  = $id;
        $this->name = $name;
        $this->shortDescription = $shortDescription;
        $this->ppe = $ppe;
        $this->devices = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return Device[]
     */
    public function equipment()
    {
        return $this->devices->toArray();
    }
}
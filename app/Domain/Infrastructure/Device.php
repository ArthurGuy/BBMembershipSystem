<?php

namespace BB\Domain\Infrastructure;

use BB\Domain\Photo;
use BB\Domain\Role;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Laracasts\Presenter\PresentableTrait;

/**
 * Class Device
 *
 * @ORM\Entity(repositoryClass="\BB\Domain\Infrastructure\DeviceRepository")
 * @ORM\Table(name="equipment")

 */
class Device
{

    use PresentableTrait;

    protected $presenter = 'BB\Presenters\EquipmentPresenter';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\JoinColumn(name="room", referencedColumnName="id")
     * @ORM\ManyToOne(targetEntity="\BB\Domain\Infrastructure\Room", inversedBy="equipment")
     */
    protected $room;

    /**
     * @ORM\JoinColumn(name="managing_role_id", referencedColumnName="id")
     * @ORM\ManyToOne(targetEntity="\BB\Domain\Role")
     */
    protected $role;

    /**
     * @ORM\Column(type="string", name="detail")
     */
    protected $roomDetail;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     * TODO: Proper settings
     */
    protected $description;

    /**
     * @ORM\Column(type="string")
     * TODO: Proper settings
     */
    protected $helpText;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $slug;

    /**
     * @ORM\Embedded(class="DeviceCost", columnPrefix=false)
     */
    protected $cost;

    /**
     * @ORM\Embedded(class="Ownership", columnPrefix=false)
     */
    protected $owner;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $working;

    /**
     * @ORM\Embedded(class="DeviceProperties", columnPrefix=false)
     */
    private $properties;

    /**
     * @ORM\Column(type="CarbonDateTime")
     */
    protected $obtainedAt;

    /**
     * @ORM\Column(type="json_array")
     */
    protected $ppe;

    /**
     * @ORM\Column(type="jsonPhotoCollection")
     */
    protected $photos;

    /**
     * Device constructor.
     *
     * @param $name
     * @param $slug
     */
    public function __construct($name, $slug)
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->working = false;
        $this->permaloan = false;
        $this->cost = new DeviceCost(false, null, 0, 0, null);
        $this->photos = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function slug()
    {
        return $this->slug;
    }

    /**
     * @return mixed
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return DeviceCost
     */
    public function cost()
    {
        return $this->cost;
    }

    /**
     * @return mixed
     */
    public function working()
    {
        return $this->working;
    }

    /**
     * @return bool
     */
    public function isWorking()
    {
        return (bool)$this->working;
    }

    /**
     * @param DeviceCost $deviceCost
     */
    public function setCost(DeviceCost $deviceCost)
    {
        $this->cost = $deviceCost;
    }

    /**
     * @param DeviceProperties $properties
     */
    public function setProperties(DeviceProperties $properties)
    {
        $this->properties = $properties;
    }

    /**
     * @param Ownership $ownership
     */
    public function setOwnership(Ownership $ownership)
    {
        $this->owner = $ownership;
    }

    /**
     * @return DeviceProperties
     */
    public function properties()
    {
        return $this->properties;
    }

    /**
     * @return Room
     */
    public function room()
    {
        return $this->room;
    }

    /**
     * @return Ownership
     */
    public function owner()
    {
        return $this->owner;
    }

    /**
     * @return mixed
     */
    public function roomDetail()
    {
        return $this->roomDetail;
    }

    /**
     * @return mixed
     */
    public function obtainedAt()
    {
        return $this->obtainedAt;
    }

    /**
     * @return bool
     */
    public function isManagedByGroup()
    {
        return (bool)$this->role();
    }

    /**
     * @return Role
     */
    public function role()
    {
        return $this->role;
    }

    /**
     * @return mixed
     */
    public function description()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function helpText()
    {
        return $this->helpText;
    }

    /**
     * @return array
     */
    public function ppe()
    {
        return $this->ppe;
    }

    /**
     * @return Photo[]
     */
    public function photos()
    {
        return $this->photos;
    }

    public function hasPhoto()
    {
        return $this->getNumPhotos() > 0;
    }

    public function getNumPhotos()
    {
        return count($this->photos());
    }

    public function hasActivity()
    {
        return false;
    }


}
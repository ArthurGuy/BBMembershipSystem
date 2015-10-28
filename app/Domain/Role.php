<?php

namespace BB\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Room
 *
 * @ORM\Entity(repositoryClass="\BB\Domain\RoleRepository")
 *
 */
class Role
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * Room constructor.
     *
     * @param $id
     * @param $name
     * @param $title
     * @param $description
     */
    public function __construct($id, $name, $title, $description=null)
    {
        $this->id  = $id;
        $this->name = $name;
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function title()
    {
        return $this->title;
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
    public function id()
    {
        return $this->id;
    }

}
<?php

namespace BB\Domain\Infrastructure;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Ownership
{

    /**
     * @ORM\Column(type="boolean")
     */
    private $permaloan;

    /**
     * @ORM\Column(type="integer")
     */
    private $permaloanUserId;

    /**
     * @ORM\Column(type="string", name="managing_role_id")
     */
    private $role;

    /**
     * Cost constructor.
     *
     * @param int  $role            What group is responsible for this
     * @param bool $permaloan
     * @param int  $permaloanUserId
     */
    public function __construct($role, $permaloan, $permaloanUserId)
    {
        $this->role            = $role;
        $this->permaloan       = $permaloan;
        $this->permaloanUserId = $permaloanUserId;
    }

    /**
     * @return mixed
     */
    public function role()
    {
        return $this->role;
    }

    /**
     * @return bool
     */
    public function permaloan()
    {
        return $this->permaloan;
    }

    /**
     * @return mixed
     */
    public function permaloanUserId()
    {
        return $this->permaloanUserId;
    }

    /**
     * @return bool
     */
    public function isPermaloan()
    {
        return (bool)$this->permaloan;
    }

}
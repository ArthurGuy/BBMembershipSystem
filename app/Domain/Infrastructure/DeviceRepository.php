<?php

namespace BB\Domain\Infrastructure;

use Doctrine\ORM\EntityRepository;

/**
 * RoomRepository
 *
 */
class DeviceRepository extends EntityRepository
{
    /**
     * @param $slug
     * @return Device
     */
    public function findBySlug($slug)
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    public function add(Device $device)
    {
        $this->getEntityManager()->persist($device);
    }

    public function remove(Device $device)
    {
        $this->getEntityManager()->remove($device);
    }

    /**
     * @return array
     */
    public function devicesRequiringInduction()
    {
        $devices = $this->findAll();
        return array_filter($devices->toArray(), function($d) {
            if ($d->cost()->requiresInduction()) {
                return true;
            }
            return false;
        });
    }

    /**
     * @return array
     */
    public function devicesNotRequiringInduction()
    {
        $devices = $this->findAll();
        return array_filter($devices->toArray(), function($d) {
            if (!$d->cost()->requiresInduction()) {
                return true;
            }
            return false;
        });
    }


}

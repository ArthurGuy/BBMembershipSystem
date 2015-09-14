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
     * @param $key
     * @return Device
     */
    public function findByKey($key)
    {
        return $this->findOneBy(['key' => $key]);
    }

    public function add(Device $device)
    {
        $this->getEntityManager()->persist($device);
    }

    public function remove(Device $device)
    {
        $this->getEntityManager()->remove($device);
    }


}

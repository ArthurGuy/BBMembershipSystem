<?php

namespace BB\Domain\Infrastructure;

use Doctrine\ORM\EntityRepository;

/**
 * RoomRepository
 *
 */
class RoomRepository extends EntityRepository
{
    /**
     * @param $key
     * @return Room
     */
    public function findByKey($key)
    {
        return $this->findOneBy(['key' => $key]);
    }

    public function add(Room $room)
    {
        $this->getEntityManager()->persist($room);
    }

    public function remove(Room $room)
    {
        $this->getEntityManager()->remove($room);
    }


}

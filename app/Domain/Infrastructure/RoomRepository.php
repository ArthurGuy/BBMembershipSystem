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
     * @param $id
     * @return Room
     */
    public function findById($id)
    {
        return $this->findOneBy(['id' => $id]);
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

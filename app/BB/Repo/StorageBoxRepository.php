<?php namespace BB\Repo;

class StorageBoxRepository extends DBRepository {

    /**
     * @var StorageBox
     */
    protected $model;

    function __construct(\StorageBox $model)
    {
        $this->model = $model;
    }

    /**
     * Fetch a members box
     * @param $userId
     * @return null|\StorageBox
     */
    public function getMemberBox($userId)
    {
        return $this->model->findMember($userId);
    }

    /**
     * Get all the active boxes
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->where('active', 1)->get();
    }

    /**
     * Get the number of available boxes
     * @return int
     */
    public function numAvailableBoxes()
    {
        $boxes = $this->getAll();
        $availableBoxes = 0;
        foreach ($boxes as $box) {
            if (empty($box->user_id)) {
                $availableBoxes++;
            }
        }
        return $availableBoxes;
    }


} 
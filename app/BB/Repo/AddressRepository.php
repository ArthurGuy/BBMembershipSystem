<?php namespace BB\Repo;

use BB\Entities\Address;
use Carbon\Carbon;

class AddressRepository extends DBRepository {


    /**
     * @var Address
     */
    protected $model;

    function __construct(Address $model)
    {
        $this->model = $model;
    }

    /**
     * @param integer $userId
     * @param array   $addressFields
     * @param boolean $isAdminUpdating Is the user making the change an admin
     * @return mixed
     */
    public function updateUserAddress($userId, array $addressFields, $isAdminUpdating)
    {
        //If the user is an admin update the main address, otherwise create a temporary record so it can be approved

        $address = null;
        if ($isAdminUpdating) {
            $address = $this->getActiveUserAddress($userId);
        }

        if (!$address) {
            $address = $this->getNewUserAddress($userId);
            if (!$address) {
                return $this->createUserAddress($userId, $addressFields, $isAdminUpdating);
            }
        }

        $address->update($addressFields);
    }

    /**
     * @param integer $userId
     * @param array   $addressFields
     * @param bool    $isAdminCreating
     * @return mixed
     */
    public function createUserAddress($userId, array $addressFields, $isAdminCreating)
    {
        $addressFields['user_id'] = $userId;
        $newRecord = $this->model->create($addressFields);

        if ($isAdminCreating) {
            $newRecord->approved = true;
            $newRecord->save();
        }
        return $newRecord;
    }

    /**
     * @param integer $userId
     * @return mixed
     */
    public function getNewUserAddress($userId)
    {
        return $this->model->where('user_id', $userId)->where('approved', false)->first();
    }

    /**
     * @param integer $userId
     * @return mixed
     */
    public function getActiveUserAddress($userId)
    {
        return $this->model->where('user_id', $userId)->where('approved', true)->first();
    }

}
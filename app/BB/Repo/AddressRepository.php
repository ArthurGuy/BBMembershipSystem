<?php namespace BB\Repo;

use BB\Entities\Address;

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

        //Format the postcode
        if (isset($addressFields['postcode'])) {
            $addressFields['postcode'] = strtoupper($addressFields['postcode']);
        }

        $address = null;
        if ($isAdminUpdating) {
            $address = $this->getActiveUserAddress($userId);
        }

        if (!$address) {
            $address = $this->getNewUserAddress($userId);
            if (!$address) {
                return $this->saveUserAddress($userId, $addressFields, $isAdminUpdating);
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
    public function saveUserAddress($userId, array $addressFields, $isAdminCreating)
    {
        $addressFields['user_id'] = $userId;

        //Format the postcode
        if (isset($addressFields['postcode'])) {
            $addressFields['postcode'] = strtoupper($addressFields['postcode']);
        }

        $newRecord = $this->model->create($addressFields);

        if ($isAdminCreating) {
            $newRecord->approved = true;
            $newRecord->save();
        }
        return $newRecord;
    }

    /**
     * Fetch a members address that hasn't been approved yet
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

    /**
     * Approve a members pending address and remove the old one
     * @param $userId
     */
    public function approvePendingMemberAddress($userId)
    {
        $address = $this->getNewUserAddress($userId);
        $oldAddress = $this->getActiveUserAddress($userId);
        $oldAddress->delete();
        $address->approved = true;
        $address->save();
    }

    /**
     * Decline a members new address and delete it
     * @param $userId
     */
    public function declinePendingMemberAddress($userId)
    {
        $address = $this->getNewUserAddress($userId);
        $address->delete();
    }

}
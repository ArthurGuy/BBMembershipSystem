<?php namespace BB\Repo;

use BB\Entities\ProfileData;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProfileDataRepository extends DBRepository
{

    /**
     * @var ProfileData
     */
    protected $model;

    public function __construct(ProfileData $model)
    {
        $this->model = $model;
    }

    /**
     * Create an empty profile for a user
     * @param $userId
     * @return mixed
     */
    public function createProfile($userId)
    {
        $new = new $this->model;
        $new->user_id = $userId;
        $new->save();
        return $new->id;
    }

    /**
     * Fetch a users profile data
     * @param $userId
     * @return mixed
     */
    public function getUserProfile($userId)
    {
        $record = $this->model->where('user_id', $userId)->first();
        if (!$record) {
            throw new ModelNotFoundException;
        }
        return $record;
    }

    public function update($userId, $profileDate)
    {
        return $this->getUserProfile($userId)->update($profileDate);
    }
} 
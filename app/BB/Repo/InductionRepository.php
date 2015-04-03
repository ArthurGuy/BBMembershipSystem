<?php namespace BB\Repo;

use Illuminate\Database\Eloquent\Collection;

class InductionRepository extends DBRepository {

    /**
     * @var \Induction
     */
    protected $model;

    function __construct(\Induction $model)
    {
        $this->model = $model;
    }


    /**
     * @return array
     */
    public function getTrainersByEquipment()
    {
        $trainersRaw = $this->model->with('user', 'user.profile')->where('is_trainer', true)->get();
        $trainers = [];
        foreach ($trainersRaw as $trainer) {
            if (isset($trainer->user->name) && $trainer->user->active)
            {
                $trainers[$trainer->key][] = $trainer->user;
            }
        }
        return $trainers;
    }

    /**
     * @param $deviceId
     * @return array
     */
    public function getTrainersForEquipment($deviceId)
    {
        $trainers = $this->model->with('user', 'user.profile')->where('is_trainer', true)->where('key', $deviceId)->get();
        return $trainers->filter(function($trainer)
        {
            return $trainer->user->active;
        });
    }


    /**
     * Get all the users who have been trained on a piece of equipment
     * @param string $deviceId
     * @return Collection
     */
    public function getUsersForEquipment($deviceId)
    {
        $users = new Collection();
        $inductionUsers = $this->model->with('user')->whereHas('user', function($q) {
            $q->where('active', '=', true);
        })->where('trained', '!=', '')->where('key', $deviceId)->get();

        //Extract the users from the inductions and place into a new collection
        foreach ($inductionUsers as $inductedUser) {
            $users->add($inductedUser->user);
        }
        return $users;
    }


    /**
     * @return array
     */
    public function getUsersPendingInduction()
    {
        $usersRaw = $this->model->with('user', 'user.profile')->where('paid', true)->whereNull('trained')->get();
        $users = [];
        foreach ($usersRaw as $induction) {
            if (isset($induction->user->name) && $induction->user->active)
            {
                $users[$induction->key][] = $induction->user;
            }
        }
        return $users;
    }

    /**
     * @return array
     */
    public function getTrainedUsers()
    {
        $usersRaw = $this->model->with('user', 'user.profile')->where('paid', true)->whereNotNull('trained')->get();
        $users = [];
        foreach ($usersRaw as $induction) {
            if (isset($induction->user->name) && $induction->user->active)
            {
                $users[$induction->key][] = $induction->user;
            }
        }
        return $users;
    }

    /**
     * @param string $device
     * @return mixed
     */
    public function getTrainedUsersForEquipment($device)
    {
        $users = $this->model->with('user', 'user.profile')->where('paid', true)->whereNotNull('trained')->where('key', $device)->get();
        return $users->filter(function($trainer)
        {
            return $trainer->user->active;
        });
    }

    /**
     * @param string $device
     * @return mixed
     */
    public function getUsersPendingInductionForEquipment($device)
    {
        $users = $this->model->with('user', 'user.profile')->where('paid', true)->where('key', $device)->whereNull('trained')->get();
        return $users->filter(function($trainer)
        {
            return $trainer->user->active;
        });
    }

    /**
     * @param $userId
     * @param string $device
     * @return bool
     */
    public function isUserTrained($userId, $device)
    {
        $record = $this->model->with('user', 'user.profile')->where('paid', true)->whereNotNull('trained')->where('user_id', $userId)->where('key', $device)->first();
        if ($record) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * @param $userId
     * @param string $device
     * @return mixed
     */
    public function getUserForEquipment($userId, $device)
    {
        $record = $this->model->with('user', 'user.profile')->where('user_id', $userId)->where('key', $device)->first();
        if ($record) {
            return $record;
        }
        return false;
    }

    /**
     * Fetch an induction record by its associated payment
     * @param $paymentId
     * @return mixed
     */
    public function getByPaymentId($paymentId)
    {
        return $this->model->where('payment_id', $paymentId)->first();
    }
} 
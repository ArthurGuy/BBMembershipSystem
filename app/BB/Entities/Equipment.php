<?php namespace BB\Entities;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'equipment';

    protected $fillable = [
        'name', 'manufacturer', 'model_number', 'serial_number', 'colour', 'location', 'room', 'detail', 'key',
        'device_key', 'description', 'help_text', 'owner_role_id', 'requires_induction', 'working',
        'permaloan', 'permaloan_user_id', 'access_fee', 'photo', 'archive', 'obtained_at', 'removed_at',
    ];

    public function getDates()
    {
        return array('created_at', 'updated_at', 'obtained_at', 'removed_at');
    }

    /**
     * Does the equipment have activity recorded against it
     *
     * @return bool
     */
    public function hasActivity()
    {
        return !empty($this->device_key);
    }

    /**
     * Does the equipment need an induction to use it
     *
     * @return mixed
     */
    public function requiresInduction()
    {
        return $this->requires_induction;
    }
} 
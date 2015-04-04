<?php namespace BB\Entities;

use Illuminate\Database\Eloquent\Model;

class StorageBox extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'storage_boxes';


    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'size',
        'active',
        'user_id'
    ];


    public function user()
    {
        return $this->belongsTo('\BB\Entities\User');
    }

    /**
     * Return a box record for the specified user
     * @param $userId
     * @return StorageBox|null
     */
    public static function findMember($userId)
    {
        return self::where('user_id', '=', $userId)->first();
    }

    public function getAvailableAttribute()
    {
        return ($this->active && !$this->user_id);
    }


} 
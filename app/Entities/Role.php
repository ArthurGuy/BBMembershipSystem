<?php namespace BB\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Role extends Model
{

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['name', 'title'];

    public function users()
    {
        return $this->belongsToMany('\BB\Entities\User')->withTimestamps();
    }

    public static function findByName($name) {
        $role = self::where('name', $name)->first();
        if ($role) {
            return $role;
        }
        throw new ModelNotFoundException();
    }

}
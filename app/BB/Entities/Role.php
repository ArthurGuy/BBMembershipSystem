<?php namespace BB\Entities;

use Illuminate\Database\Eloquent\Model;

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

}
<?php namespace BB\Entities;

class Role extends \Eloquent {

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
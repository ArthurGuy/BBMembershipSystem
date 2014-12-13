<?php

class Role extends Eloquent {

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany('User')->withTimestamps();
    }

}
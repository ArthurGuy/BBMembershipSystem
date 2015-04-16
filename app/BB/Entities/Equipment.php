<?php namespace BB\Entities;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'equipment';

    public function getDates()
    {
        return array('created_at', 'updated_at', 'obtained_at', 'removed_at');
    }

} 
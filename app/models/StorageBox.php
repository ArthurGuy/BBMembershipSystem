<?php 

class StorageBox extends Eloquent {

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
        'size', 'active'
    ];


    public function user()
    {
        return $this->belongsTo('User');
    }


} 
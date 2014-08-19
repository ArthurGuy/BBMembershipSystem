<?php

class KeyFob extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'key_fobs';

    protected $fillable = [
        'user_id',
        'key_id'
    ];

    protected $attributes = [
        'active' => 1,
        'lost'   => 0,
    ];

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function markLost()
    {
        $this->lost = true;
        $this->active = false;
        $this->save();
    }

} 
<?php 

class AuditLog extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'audit_log';


    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'admin_id', 'action', 'description'
    ];


    public function user()
    {
        return $this->belongsTo('User');
    }

    public function admin()
    {
        return $this->belongsTo('User', 'admin_id');
    }


} 
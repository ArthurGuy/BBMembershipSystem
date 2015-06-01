<?php namespace BB\Entities;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{

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
        return $this->belongsTo('\BB\Entities\User');
    }

    public function admin()
    {
        return $this->belongsTo('\BB\Entities\User', 'admin_id');
    }


} 
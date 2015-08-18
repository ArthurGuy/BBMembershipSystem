<?php

namespace BB\Entities;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'message', 'type', 'hash'];
}

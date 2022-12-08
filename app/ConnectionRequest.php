<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConnectionRequest extends Model
{

    protected $table = 'connectionrequest';

    protected $fillable = [
        'connection_id',
        'auth_id',
        'user_id'
    ];

}

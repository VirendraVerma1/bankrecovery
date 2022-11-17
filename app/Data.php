<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{

    protected $table = 'data';

    protected $fillable = [
        'agreementno',
        'region',
        'branch',
        'customername',
        'gv',
        'make_model',
        'regdnum',
        'chasisnum',
        'enginenum',
        'rrmname',
        'rrmemail',
        'expirydate',
    ];

}

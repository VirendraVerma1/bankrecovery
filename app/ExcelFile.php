<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExcelFile extends Model
{

    protected $table = 'excelfile';

    protected $fillable = [
        'filename',
        'storedname',
    ];

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Boardingfeesdetail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'emp_id','boardingfees_id','status',
         'create_by', 'update_by'
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employeeloandetail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'emp_id','loan','employeeloan_id','status',
         'create_by', 'update_by'
    ];
}

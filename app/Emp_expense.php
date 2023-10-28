<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Emp_expense extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'employee_id','cost','expenses_type','month','status',
         'create_by'
    ];
}

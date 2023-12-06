<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salaryadvancedetail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'emp_id','amount','salaryadvance_id','status',
         'create_by', 'update_by'
    ];
}

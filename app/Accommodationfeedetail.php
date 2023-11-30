<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accommodationfeedetail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'emp_id','accommodationfee','company_discount','total_cost','accommodationfee_id','status',
         'create_by', 'update_by'
    ];
}

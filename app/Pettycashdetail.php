<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pettycashdetail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'bill_date','emp_type','emp_id','non_reg_emp','bill_no','description','cost','float_balance','category','pettycash_id', 'status',
         'create_by', 'update_by'
    ];
}

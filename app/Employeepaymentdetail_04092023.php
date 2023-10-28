<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employeepaymentdetail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'employee_payment_id', 'job_title_id', 'holiday_type_id', 'shift_id', 'companyrate', 'guardrate','status',
         'create_by', 'update_by'
    ];
}

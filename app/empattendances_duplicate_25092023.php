<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class empattendances_duplicate extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'date','attendance_id', 'allocation_id','subcustomer_id', 'customerbranch_id','holiday_id','shift_id', 'emp_id', 'jobtitle_id',
        'ontime','outtime','attendance_status','status', 'approve_status', 'approve_01', 'approve_01_time','approve_01_by',
       'approve_02', 'approve_02_time','approve_02_by',
        'approve_03', 'approve_03_time','approve_03_by', 'delete_status',
         'create_by', 'update_by'
    ];
}

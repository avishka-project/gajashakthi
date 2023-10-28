<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class empallocation extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'request_id', 'customer_id','subcustomer_id', 'customerbranch_id','date','holiday_id', 'shift_id','jobtitle_id','count', 'manager_id','status','allocatedstatus', 'approve_status', 'approve_01', 'approve_01_time','approve_01_by',
       'approve_02', 'approve_02_time','approve_02_by',
        'approve_03', 'approve_03_time','approve_03_by', 'delete_status',
         'create_by', 'update_by'
    ];
}

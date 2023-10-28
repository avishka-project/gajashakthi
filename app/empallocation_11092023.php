<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class empallocation extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'request_id', 'customer_id','subcustomer_id', 'customerbranch_id','subregion_id','date','holiday_id', 'shift_id', 'manager_id','status','allocatedstatus', 
       'approve_status', 'approve_01', 'approve_01_time','approve_01_by',
       'approve_02', 'approve_02_time','approve_02_by',
       'approve_03', 'approve_03_time','approve_03_by',
       'specialrequest_approvestatus', 'specialrequest_approve01', 'specialrequest_approve01_time', 'specialrequest_approve01_by',
       'specialrequest_approve02', 'specialrequest_approve02_time', 'specialrequest_approve02_by', 
       'specialrequest_approve03', 'specialrequest_approve03_time', 'specialrequest_approve03_by',
        'delete_status',
         'create_by', 'update_by'
    ];
}

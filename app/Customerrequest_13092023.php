<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customerrequest extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'customer_id','subcustomer_id', 'customerbranch_id','subregion_id', 'fromdate', 'todate', 'shift_id','status','allocationstatus', 'approve_status', 'approve_01', 'approve_01_time','approve_01_by',
        'approve_02', 'approve_02_time','approve_02_by',
        'approve_03', 'approve_03_time','approve_03_by',
         'create_by', 'update_by'
    ];
}

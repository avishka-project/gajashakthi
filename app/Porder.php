<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Porder extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'order_date','due_date','sub_total','discount','discount_amount',
        'net_total','confirm_status','grn_status','remark','employee_id',
        'status','approve_status', 'approve_01', 'approve_01_time','approve_01_by',
        'approve_02', 'approve_02_time','approve_02_by',
        'approve_03', 'approve_03_time','approve_03_by','supplier_id','store_id',
         'create_by', 'update_by'
    ];
}

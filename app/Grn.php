<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grn extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'batch_no','grn_date','total','invoice_no','dispatch_no',
        'confirm_status','remark',
        'status','approve_status', 'approve_01', 'approve_01_time','approve_01_by',
        'approve_02', 'approve_02_time','approve_02_by',
        'approve_03', 'approve_03_time','approve_03_by','supplier_id','porder_id',
         'create_by', 'update_by'
    ];
}

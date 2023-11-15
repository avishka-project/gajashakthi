<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Returnlist extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'location_id','department_id','employee_id','month','issue_type','payment_type','remark','issuing','issue_id','status','approve_status','add_to_return','return_status', 'approve_01', 'approve_01_time','approve_01_by',
        'approve_02', 'approve_02_time','approve_02_by',
        'approve_03', 'approve_03_time','approve_03_by',
         'create_by', 'update_by'
    ];
}

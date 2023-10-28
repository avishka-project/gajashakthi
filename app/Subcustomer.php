<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcustomer extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'customer_id','sub_name', 'address1', 'address2', 'city', 'subregion_id', 'pay_by', 'approve_status',
         'status','approve_01', 'approve_01_time','approve_01_by',
         'approve_02', 'approve_02_time','approve_02_by',
         'approve_03', 'approve_03_time','approve_03_by', 
         'create_by', 'update_by',
    ];
}

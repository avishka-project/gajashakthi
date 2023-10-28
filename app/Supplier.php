<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'supplier_name','address1','address2','city','payment_terms','contact_no','email','status','approve_status',
         'approve_01', 'approve_01_time','approve_01_by',
        'approve_02', 'approve_02_time','approve_02_by',
        'approve_03', 'approve_03_time','approve_03_by',
        'reject','reject_comment','reject_time','reject_by',
         'create_by', 'update_by'
    ];
}

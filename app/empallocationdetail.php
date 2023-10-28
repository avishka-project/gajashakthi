<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class empallocationdetail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[
        'allocation_id', 'assigndesignation_id', 'status','specialrequest_approve_need','delete_status',
        'create_by', 'update_by', 'shift_id', 'count'
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class empallocationdetails_duplicate extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'details_id','allocation_id', 'emp_id', 'assigndesignation_id', 'status','specialrequest_approve_need','delete_status',
         'create_by', 'update_by'
    ];
}

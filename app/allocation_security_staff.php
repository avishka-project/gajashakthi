<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class allocation_security_staff extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'customerrequest_id','fromdate', 'todate', 'client_id','subclient_id','branch_id','emp_id', 'status',
         'create_by', 'update_by'
    ];
}

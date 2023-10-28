<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customerrequestdetail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'customerrequest_id', 'job_title_id', 'count', 'status',
         'create_by', 'update_by'
    ];
}

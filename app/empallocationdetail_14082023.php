<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class empallocationdetail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'allocation_id', 'job_title_id', 'count', 'status',
         'create_by', 'update_by'
    ];
}

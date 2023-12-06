<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Newbusinessproposal_ratedetail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'newbusinessproposals_id', 'job_title_id', 'rate_type','value','status',
         'create_by', 'update_by'
    ];
}

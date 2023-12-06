<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Newbusinessproposaldetail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'newbusinessproposals_id', 'job_title_id', 'holiday_id','shift_id','count','shift_rate','salary_rate', 'status',
         'create_by', 'update_by'
    ];
}

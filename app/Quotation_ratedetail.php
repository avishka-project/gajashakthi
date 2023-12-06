<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotation_ratedetail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'quotation_id', 'job_title_id', 'rate_type','value','status',
         'create_by', 'update_by'
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotationdetail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'quotation_id', 'job_title_id', 'holiday_id','shift_id','count', 'status',
         'create_by', 'update_by'
    ];
}

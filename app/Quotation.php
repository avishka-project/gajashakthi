<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'document_no','date','client_name','client_address','fromdate','todate','holidays','special_holidays','status','approve_status', 'approve_01', 'approve_01_time','approve_01_by',
        'approve_02', 'approve_02_time','approve_02_by',
        'approve_03', 'approve_03_time','approve_03_by',
         'create_by', 'update_by'
    ];
}

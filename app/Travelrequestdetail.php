<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Travelrequestdetail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

         'emp_id','cost', 'travelrequests_id',
         'status','create_by', 'update_by',
    ];
}

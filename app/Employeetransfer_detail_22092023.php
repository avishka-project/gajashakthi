<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employeetransfer_detail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[
        'transfer_id', 'emp_id','service_no','emp_subregion_from', 'status','delete_status',
        'create_by', 'update_by'
    ];
}

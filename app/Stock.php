<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'batch_no','qty','unit_price','comment',
        'status','item_id','store_id',
         'create_by', 'update_by'
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Return_stock extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'quality_percentage','qty','unit_price','comment',
        'status','item_id','store_id',
         'create_by', 'update_by'
    ];
}

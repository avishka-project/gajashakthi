<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class inventory_list_price_summary extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'item_id','item_name','unit_price',
        'status',
         'create_by', 'update_by'
    ];
}

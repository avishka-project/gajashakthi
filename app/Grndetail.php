<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grndetail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'date','qty','unit_price','total','comment','item_description',
        'status','item_id','grn_id',
         'create_by', 'update_by'
    ];
}

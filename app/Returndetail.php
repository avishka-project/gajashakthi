<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Returndetail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'item_id','rate','qty','total','storelist_id','asset_value','return_id','status',
         'create_by', 'update_by'
    ];
}

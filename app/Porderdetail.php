<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Porderdetail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'qty','grn_issue_qty','unit_price','total','discount','discount_amount','comment',
        'status','inventorylist_id','porder_id',
         'create_by', 'update_by'
    ];
}

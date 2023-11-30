<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Porderdetail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'qty','grn_issue_qty','unit_price','total','vat_precentage','vat_amount','total_after_vat','discount','discount_amount','comment',
        'status','inventorylist_id','porder_id',
         'create_by', 'update_by'
    ];
}

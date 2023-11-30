<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grndetail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'date','qty','unit_price','total','vat_precentage','vat_amount','total_after_vat','comment','item_description',
        'status','item_id','grn_id','porderdetail_id',
         'create_by', 'update_by'
    ];
}

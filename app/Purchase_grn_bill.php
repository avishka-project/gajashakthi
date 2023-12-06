<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase_grn_bill extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'purchase_id','grn_id','refno','created_by'
    ];
}

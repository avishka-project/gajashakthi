<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fixed_asset extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'code','asset_category_id','particular_id','employee_id','region','department','clientbranch','opening_value','dateofpurchase','rate','addition_deletion','closing_value','acc_dep_2022','dep_2023','acc_dep_2023','writtendown_2023','status','approve_status', 'approve_01', 'approve_01_time','approve_01_by',
        'approve_02', 'approve_02_time','approve_02_by',
        'approve_03', 'approve_03_time','approve_03_by',
         'create_by', 'update_by'
    ];
}

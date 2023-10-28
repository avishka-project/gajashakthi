<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee_dependent extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'life_status'
    ];
}

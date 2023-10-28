<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = [
        'bank', 'code', 'zipcode','status', 'create_by',
    ];
}

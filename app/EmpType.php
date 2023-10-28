<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmpType extends Model
{
    protected $table = 'emptypes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'emptype'
    ];

}

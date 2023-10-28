<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deaddonationincompletedetail extends Model
{
    protected $primarykey = 'id';

    protected $fillable =[

        'filename','deaddonationincompletes_id','status',
         'create_by', 'update_by'
    ];
}

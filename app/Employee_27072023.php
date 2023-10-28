<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public function country()

    {

        return $this->belongsTo(Attendance::class);

    }
}

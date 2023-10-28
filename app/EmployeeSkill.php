<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeSkill extends Model
{
    public function skill()
    {
        return $this->belongsTo(Skill::class, 'emp_skill', 'id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';
    protected $primaryKey = 'id';

    public function country()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    public function sub_region()
    {
        return $this->belongsTo(Subregion::class, 'subregion_id', 'id');
    }

    public function emp_type()
    {
        return $this->belongsTo(EmpType::class, 'emptype_id', 'id');
    }
}

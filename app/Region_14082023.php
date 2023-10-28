<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'regions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'region'
    ];

    public function sub_regions()
    {
        return $this->hasMany('App\SubRegion', 'region_id', 'id');
    }
}

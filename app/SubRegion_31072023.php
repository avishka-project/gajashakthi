<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubRegion extends Model
{
    protected $table = 'subregions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'subregion',
        'region_id'
    ];

    public function region()
    {
        return $this->belongsTo('App\Region', 'region_id', 'id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CsiState extends Model
{
    protected $fillable = ['csi_region_id', 'state_code'];

    public function scopeFilterByRegion($query, $id){
    	return $query->where('csi_region_id', $id);
    }

    public function region(){
        return $this->hasOne('App\CsiRegion', 'id', 'csi_region_id');
    }

    public function state(){
        return $this->hasOne('App\State', 'state_code', 'state_code');
    }
}

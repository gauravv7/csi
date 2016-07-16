<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CsiChapter extends Model
{
    protected $fillable = ['csi_state_code', 'name'];

    public function scopeFilterByStateCode($query, $code)
    {
    	return $query->where('csi_state_code', $code);
    }

    public function scopeFilterByStateName($query, $name)
    {
    	return $query->where('name', $name);
    }

    public function state(){
        return $this->hasOne('App\CsiState', 'state_code', 'csi_state_code');
    }

}

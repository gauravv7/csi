<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicePeriod extends Model
{
    protected $fillable = ['membership_type_id', 'service_id', 'years', 'name'];


    public function scopeGetPeriodsByType($query, $id){
    	return $query->where('membership_type_id', $id);
    }

    public function scopeGetPeriodsByTypeAndDuration($query, $id, $years){
    	return $query->where('membership_type_id', $id)->where('years', $years);
    }

}


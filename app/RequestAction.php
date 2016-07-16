<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestAction extends Model
{
    protected $fillable = ['request_id', 'status', 'comment'];

    public function scopeActionsByRequestIdAndStatus($query, $id, $status){
    	return $query->where('request_id', $id)->where('status', $status);
    }

    public function scopeActionsByRequestId($query, $id){
    	return $query->where('request_id', $id);
    }

    public function scopeActionsByStatus($query, $status){
    	return $query->where('status', $status);
    }

    public function request() {
        return $this->hasOne('App\Request', 'id', 'request_id');
    }
}

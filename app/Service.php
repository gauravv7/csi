<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
	protected $fillable = ['name'];

	public function scopeGetServiceIDByType($query, $type){
		return $query->where('name', $type)->first()->id;
	}
}

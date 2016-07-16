<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstitutionType extends Model
{
    protected $fillable = ['membership_type_id', 'name'];

    public function scopeGetInstitutionTypesById($query, $id){
    	return $query->where('membership_type_id', $id);
    }
}

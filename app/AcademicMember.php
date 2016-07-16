<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcademicMember extends Model
{
	protected $fillable = ['id', 'institution_type_id', 'is_student_branch', 'rejection_reason'];

	public function institution() {
		return $this->hasOne('App\Institution', 'id', 'id');
	}

	public function scopeGetInInstitutionType($query, $types) {
		return $query->whereIn('institution_type_id', $types);
	}

	public function InstitutionType() {
        return $this->hasOne('App\InstitutionType', 'id', 'institution_type_id');
    }

 	public function scopeIsStudentBranch($query){
		return $query->where('is_student_branch', 1);
	}   
}

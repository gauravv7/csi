<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentMember extends Model
{
    protected $fillable = ['id', 'student_branch_id','course_name','course_duration','college_name','course_branch','proof_id' ];

    public function setCourseBranchAttribute($course_branch) {
        $this->attributes['course_branch'] = trim($course_branch) !== '' ? $course_branch : null;
    }

    public function institution(){
        return $this->hasOne('App\Institution', 'member_id', 'student_branch_id');
    }
}

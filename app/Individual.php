<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Individual extends Model
{
    protected $fillable = ['member_id', 'membership_type_id', 'salutation_id', 'first_name', 'middle_name', 'last_name', 'card_name', 'gender', 'dob'];

    public function getName(){
        return $this->salutation->name.". ".$this->attributes['first_name']." ".$this->attributes['middle_name']." ".$this->attributes['last_name'];
    }

    public function getMobile(){
        return $this->member->phone->mobile;
    }

    public function setMnameAttribute($mname){
        $this->attributes['mname'] = trim($mname) !== '' ? $mname : null;
    }
    public function setLnameAttribute($lname){
        $this->attributes['lname'] = trim($lname) !== '' ? $lname : null;
    }

    public function getDobAttribute($dob){
        return Carbon::parse($dob);
    }

    public function setDobAttribute($dob){
        $this->attributes['dob'] = Carbon::createFromFormat('d/m/Y', $dob)->format('Y-m-d');
    }

    public function membershipType(){
        return $this->hasOne('App\MembershipType', 'id', 'membership_type_id');
    }

    public function subType() {
        if($this->membershipType->type == 'student') {
            return $this->hasOne('App\StudentMember', 'id', 'id');
        } elseif ($this->membershipType->type == 'professional') {
            return $this->hasOne('App\ProfessionalMember', 'id', 'id');
        }
    }

    public function scopeGetAllStudents($query) {
        return $query->where('membership_type_id', 3);
    }

    public function scopeGetAllProfessionals($query) {
        return $query->where('membership_type_id', 4);
    }

    public function member() {
        return $this->hasOne('App\Member', 'id', 'member_id');
    }

    public function salutation() {
        return $this->hasOne('App\Salutation', 'id', 'salutation_id');
    }
}

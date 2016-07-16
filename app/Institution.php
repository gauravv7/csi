<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    protected $fillable = ['member_id', 'membership_type_id', 'salutation_id', 'name', 'head_name', 'head_designation', 'email', 'country_code', 'mobile'];

    public function membershipType(){
        return $this->hasOne('App\MembershipType', 'id', 'membership_type_id');
    }

    public function getName(){
        return $this->attributes['name'];
    }

    public function getMobile(){
        return $this->mobile;
    }

    public function subType() {
        if($this->membershipType->type == 'academic') {
            return $this->hasOne('App\AcademicMember', 'id', 'id');
        } elseif ($this->membershipType->type == 'non-academic') {
            return $this;
        }
    }

    public function scopeGetInIds($query, $ids) {
        return $query->whereIn('id', $ids);
    }

    public function scopeGetAllAcademicInstitutions($query) {
        return $query->where('membership_type_id', 1);
    }

    public function scopeGetAllNonAcademicInstitutions($query) {
        return $query->where('membership_type_id', 2);
    }

    public function member() {
        return $this->hasOne('App\Member', 'id', 'member_id');
    }

    public function salutation() {
        return $this->hasOne('App\Salutation', 'id', 'salutation_id');
    }

    public function InstitutionType() {
        return $this->hasOne('App\InstitutionType', 'id', 'institution_type');
    }

}

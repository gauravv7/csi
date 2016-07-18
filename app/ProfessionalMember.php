<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfessionalMember extends Model
{
    protected $fillable = ['id', 'associating_institution_id','organisation', 'designation', 'is_nominee','proof_id'];

    public function getDateOfEffectAttribute($nominee_effective){
        return ($nominee_effective == '0000-00-00')? null: Carbon::parse($nominee_effective);
    }

    public function setDateOfEffectAttribute($nominee_effective){
        $this->attributes['nominee_effective'] = Carbon::createFromFormat('d/m/Y', $nominee_effective)->format('Y-m-d');
    }

    public function setAssociatingInstitutionIdAttribute($associating_institution_id){
        $this->attributes['associating_institution_id'] = (is_integer(intval($associating_institution_id))) ? $associating_institution_id : null;
    }

    public function scopeHasAssociatingInstitution($query) {
    	return $query->whereNotNull('associating_institution_id')->where('id', $this->id);
    }

    public function institution() {
        return $this->hasOne('App\Institution', 'id', 'associating_institution_id');
    }

    public function individual() {
        return $this->hasOne('App\Individual', 'id', 'id');
    }

}

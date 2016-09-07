<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\MyHelpers\HFunctions;

class BulkPayment extends Model
{
    protected $fillable = ['institution_id', 'member_count', 'calculated_amount'];

    public function getDateOfPaymentAttribute($created_at){
        return ($created_at == '0000-00-00')? null: Carbon::parse($created_at);
    }

    public function scopeFilterByInstitution($query, $id){
        return $query->where('institution_id', $id);
    }

    public function scopeFilterByInstitutionIDAndNarrationID($query, $id, $nid){
        return $query->where('institution_id', $id)->where('narration_id', $nid);
    }

    public function scopeFilterByNarrationID($query, $nid){
    	return $query->where('narration_id', $nid);
    }

	public function institution() {
        return $this->hasOne('App\Institution', 'id', 'institution_id');
    }

	public function narration() {
        return $this->hasOne('App\Narration', 'id', 'narration_id');
    }

    public function getFormattedAmount($country_code, $amount){
        (strcasecmp($country_code, 'IND')==0)? setlocale(LC_MONETARY, 'en_IN'): setlocale(LC_MONETARY, 'en_US');
         return (function_exists('money_format'))? money_format('%i', $amount): HFunctions::money_format('%i', $amount);
       // return money_format('%i', $amount);
    }

    public function getFormattedCalculatedAmount(){
        return $this->getFormattedAmount(Address::getRegisteredAddress($this->institution->member_id)->first()->country_code, $this->calculated_amount);
    }

    public function getFormattedNarrationAmount(){
        return $this->getFormattedAmount(Address::getRegisteredAddress($this->institution->member_id)->first()->country_code, ($this->narration)? $this->narration->drafted_amount: 0);
    }

    public function getPaidDiff(){
    	return $this->calculated_amount - (($this->narration)? $this->narration->drafted_amount: 0);
    }

    public function getFormattedPaidDiff(){
        return $this->getFormattedAmount(Address::getRegisteredAddress($this->institution->member_id)->first()->country_code, abs($this->getPaidDiff()) );
    }

}
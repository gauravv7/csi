<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\MyHelpers\HFunctions;

class PaymentHead extends Model
{
    protected $fillable = ['service_period_id' ,'currency_id' ,'service_tax_class_id' ,'amount'];

    public function scopeGetHead($query, $period, $currency_id){
    	$condition = ['service_period_id' => $period, 'currency_id' => $currency_id];
    	return $query->where($condition);
    }

    public function servicePeriod() {
        return $this->hasOne('App\ServicePeriod', 'id', 'service_period_id');
    }

    public function currency() {
        return $this->hasOne('App\Currency', 'id', 'currency_id');
    }

    public function serviceTaxClass(){
    	return $this->hasOne('App\ServiceTaxClass', 'id', 'service_tax_class_id');
    }
    
    public function getFormattedAmount(){
        ($this->currency->currency_code == 'INR')? setlocale(LC_MONETARY, 'en_IN'): setlocale(LC_MONETARY, 'en_US');
        return (function_exists('money_format'))? money_format('%i', $this->amount): HFunctions::money_format('%i', $this->amount);
    }

    public function getFormattedCalculatedAmount($calculated_amount){
        ($this->currency->currency_code == 'INR')? setlocale(LC_MONETARY, 'en_IN'): setlocale(LC_MONETARY, 'en_US');
        return (function_exists('money_format'))? money_format('%i', $calculated_amount): HFunctions::money_format('%i', $calculated_amount);

    }

    public function calculatePayable(){
        $amount = $this->amount;
        $service_tax = $this->serviceTaxClass->tax_rate;
        return ($amount + ( ($amount*$service_tax)/100 ));
    }
}


<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $fillable = ['payment_id', 'narration_id', 'paid_amount'];

    public function scopeFilterByPayment($query, $id){
        return $query->where('payment_id', $id);
    }

    public function scopeFilterByPaymentAndNarration($query, $pid, $narration_id){
        return $query->where('payment_id', $pid)->where('narration_id', $narration_id);
    }

    public function narration() {
    	return $this->hasOne('App\Narration', 'id', 'narration_id');
    }

    public function payment() {
        return $this->hasOne('App\Payment', 'id', 'payment_id');
    }

    /**
     * Set the keys for a save update query.
     * This is a fix for tables with composite keys
     * TODO: Investigate this later on
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
            //Put appropriate values for your keys here:
            ->where('payment_id', '=', $this->payment_id)
            ->where('narration_id', '=', $this->narration_id);

        return $query;
    }

    public function getFormattedPaidAmount(){
        ($this->payment->paymentHead->currency->currency_code == 'INR')? setlocale(LC_MONETARY, 'en_IN'): setlocale(LC_MONETARY, 'en_US');
        return money_format('%i', $this->paid_amount);
    }

}

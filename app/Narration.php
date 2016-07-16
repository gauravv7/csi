<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Narration extends Model
{
    protected $fillable = [ 'payer_id', 'mode', 'transaction_number', 'bank', 'branch', 'date_of_payment', 'drafted_amount', 'proof'];

    public function getDateOfPaymentAttribute($date_of_payment){
        return ($date_of_payment == '0000-00-00')? null: Carbon::parse($date_of_payment);
    }

    public function setDateOfPaymentAttribute($date_of_payment){
        $this->attributes['date_of_payment'] = Carbon::createFromFormat('d/m/Y', $date_of_payment)->format('Y-m-d');
    }

    public function paymentMode() {
        return $this->hasOne('App\PaymentMode', 'id', 'mode');
    }

    public function payer(){
        return $this->hasOne('App\Member', 'id', 'payer_id');
    }

    public function journals() {
        return $this->hasMany('App\Journal', 'narration_id', 'id');
    }

    public function getFormattedDraftedAmount(){
        ($this->journals->first()->payment->paymentHead->currency->currency_code == 'INR')? setlocale(LC_MONETARY, 'en_IN'): setlocale(LC_MONETARY, 'en_US');
        return money_format('%i', $this->drafted_amount);
    }
}

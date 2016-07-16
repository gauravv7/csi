<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['paid_for', 'payment_head_id', 'service_id'];

    public function getDateOfEffectAttribute($date_of_effect){
        return ($date_of_effect == '0000-00-00')? null: Carbon::parse($date_of_effect);
    }

    public function setDateOfEffectAttribute($date_of_effect){
        $this->attributes['date_of_effect'] = Carbon::createFromFormat('d/m/Y', $date_of_effect)->format('Y-m-d');
    }

    public function scopeFilterByPaymentHead($query, $id){
        $query->where('payment_head_id', $id);
    }

    public function scopeFilterByService($query, $id){
        $query->where('service_id', $id);
    }

    public function scopeRejected($query) {
        return $query->where('is_rejected', 1);
    }

    public function scopeVerified($query) {
        return $query->where('is_verified', 1);
    }

    public function scopeFilterByServiceAndMember($query, $id, $mid){
        $query->where('service_id', $id)->where('paid_for', $mid);
    }

    public function owner() {
        return $this->hasOne('App\Member', 'id', 'paid_for');
    }

    public function service() {
        return $this->hasOne('App\Service', 'id', 'service_id');
    }

    public function paymentHead() {
        return $this->hasOne('App\PaymentHead', 'id', 'payment_head_id');
    }

    public function requestService() {
        return $this->hasOne('App\RequestService', 'payment_id', 'id');
    }

    public function journals() {
        return $this->hasMany('App\Journal', 'payment_id', 'id');
    }

    public function calculatePayable(){
        $amount = $this->paymentHead->amount;
        $service_tax = $this->paymentHead->serviceTaxClass->tax_rate;
        return ($amount + ( ($amount*$service_tax)/100 ));
    }

    public static function getNextAllotedID(){
        return (Payment::whereNotNull('timestamp_of_effect')->count() > 0)? Payment::orderBy('timestamp_of_effect', 'desc')->first()->owner->alloted_id + 1 : 1;
    }

    /**
     * accepts a payment by filling in the currect date for date-of-effect for the payment 
     * all accepted amounts are taken into considerations 
     * @return [false] [payments are yet not verified, payments are unbalanced]
     * @return [true]  [payments are accepted and they are hence balanced]
     */
    public function AcceptMembershipPayment(){
        $final_amount = $this->calculatePayable();
        // dd(['a' => $amount, 'st' => $service_tax, 'fa' => $final_amount]);
        $effective_date = $this->date_of_effect;

        if( $effective_date == null) {
            // go through all the payment's journals
            // filter to get only accepted journals
            $paid_amount = $this->journals()->where('is_rejected', 0)->sum('paid_amount');
            // if the date_of_effect of payments is null then just take all the accepted journals and
            /**
             * ** IMPORTANT **
             * subtract the total paid amount from final drafted amount amount
             */
           $final_amount -= $paid_amount;
        }// if
        if($final_amount == 0){
            $member = $this->owner;
            $member->alloted_id = Payment::getNextAllotedID();
            $member->save();
            $this->date_of_effect = Carbon::now()->format('d/m/Y');
            $this->timestamp_of_effect = Carbon::now();
            $this->save();
        } else {
            return false;
        }
        return true;
    }

    /**
     * gets the difference for the payable amount for membership applied and amount paid till yet 
     * all non-rejected amounts are taken into considerations 
     * @param  [bool]     $[checkForOnlyAcceptedJournals] [decision parameter to check if the journals considered are accepted only journals or not]
     * @return [positive] [due amount to be paid by user]
     * @return [negative] [creditable amount to be paid by admin to user]
     */
    public function getPayableDiff($checkForOnlyAcceptedJournals = false){
        $final_amount = $this->calculatePayable();
        // go through all the payment's journals
        if($checkForOnlyAcceptedJournals){
            // filter to get non-rejected journals
            $paid_amount = $this->journals()->where('is_rejected', '=', 0)->sum('paid_amount');
        } else {
            // filter to get non-rejected journals
            $paid_amount = $this->journals()->where('is_rejected', '<>', 1)->sum('paid_amount');
        }
        // if the date_of_effect of payments is null then just take all the accepted journals and
        /**
         * ** IMPORTANT **
         * subtract the total paid amount from final drafted amount amount
         */
       return ($final_amount -= $paid_amount);
    }

    public function getFormattedTotalAmountForJournals(){
        ($this->paymentHead->currency->currency_code == 'INR')? setlocale(LC_MONETARY, 'en_IN'): setlocale(LC_MONETARY, 'en_US');
        $total = $this->journals->filter(function($item){ 
                    return ($item->is_rejected != 1)? true: false; 
                })->sum('paid_amount');
        return money_format('%i', $total);
    }

    public function getFormattedAmount($currency_code, $amount){
        ($currency_code == 'INR')? setlocale(LC_MONETARY, 'en_IN'): setlocale(LC_MONETARY, 'en_US');
        return money_format('%i', $amount);
    }

}

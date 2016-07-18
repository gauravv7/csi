<?php

namespace App;

use App\Enums\ActionStatus;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Kbwebs\MultiAuth\PasswordResets\CanResetPassword;
use Kbwebs\MultiAuth\PasswordResets\Contracts\CanResetPassword as CanResetPasswordContract;

class Member extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{


     /**
     * The database table used by the model.
     *
     * @var string
     */

	use Authenticatable, Authorizable, CanResetPassword;
	

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['membership_id', 'csi_chapter_id', 'email', 'email_extra', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function membership() {
        return $this->hasOne('App\Membership', 'id', 'membership_id');
    }

    public function scopeGetInIds($query, $ids) {
        return $query->whereIn('id', $ids);
    }

    public function scopeGetAllInstitions($query) {
        return $query->where('membership_id', 1);
    }

    /**
     * @return [Institution/Individual] [get sub-type membership]
     */
    public function getMembership() {
        if($this->membership->type == 'individual') {
            return $this->hasOne('App\Individual', 'member_id', 'id');
        } elseif ($this->membership->type == 'institutional') {
            return $this->hasOne('App\Institution', 'member_id', 'id');
        }
    }

    public function setEmailExtraAttribute($email_extra){
        $this->attributes['email_extra'] = trim($email_extra) !== '' ? $email_extra : null;
    }

    public function chapter() {
        return $this->hasOne('App\CsiChapter', 'id', 'csi_chapter_id');
    }

    public function addresses() {
        return $this->hasMany('App\Address', 'member_id', 'id');
    }

    public function phone() {
        return $this->hasOne('App\Phone', 'member_id', 'id');
    }

    public function payments() {
        return $this->hasMany('App\Payment', 'paid_for', 'id');
    }

    public function requests() {
        return $this->hasMany('App\Request', 'requested_by', 'id');
    }

    public function checkMembershipPaymentValidity(){
        $retVal = false;
        if( ($this->getMembership->membershipType->type == 'professional') && ($this->getMembership->subType->is_nominee==ActionStatus::approved) ){
            $payments = Payment::filterByServiceAndMember(1, $this->getMembership->subType->institution->member_id)->get();
        } else{
            $payments = Payment::filterByServiceAndMember(1, $this->id)->get();
        }
        if(!$payments->isEmpty()){
            foreach ($payments as $payment) {
                // just to be sure, we are checking against that payable-diff is also balanced
                // NOTE: HERE, payment-diff is based on only accepted payments 
                if( ($effective_date = $payment->date_of_effect) && (0 == $payment->getPayableDiff(true)) ){
                    if( $effective_date->addYears($payment->paymentHead->servicePeriod->years)->gte(Carbon::parse(Carbon::now()) ) ){
                        $retVal = true;
                        break;
                    }
                }
            } //foreach
        }
        return $retVal;
    }

    public function getEntity(){
        $entity = '';
        if($this){
            $entity   = ($this->membership->type == 'institutional')? 'institution-': 'individual-';
            if($this->getMembership->membershipType->type == 'academic'){
                $entity .= 'academic';
            } else if($this->getMembership->membershipType->type == 'non-academic'){
                $entity .= 'non-academic';
            } else if($this->getMembership->membershipType->type == 'student'){
                $entity .= 'student';
            } else if($this->getMembership->membershipType->type == 'professional'){
                $entity .= 'professional';
            }
        }
        return $entity;
    }

    public function getFormattedEntity(){
        $entity = '';
        if($this){
            if($this->getMembership->membershipType->type == 'academic'){
                $entity = 'academic';
            } else if($this->getMembership->membershipType->type == 'non-academic'){
                $entity = 'non-academic';
            } else if($this->getMembership->membershipType->type == 'student'){
                $entity = 'student';
            } else if($this->getMembership->membershipType->type == 'professional'){
                $entity = 'professional';
            }
            $entity   .= ($this->membership->type == 'institutional')? ' institution': ' individual';
        }
        return $entity;
    }

    public function getFullID(){
        $id  = $this->getMembership->membershipType->prefix;
        $id .= '-';
        $id .= $this->id;
        return $id;
    }

    public function getFullAllotedID(){
        $id  = $this->getMembership->membershipType->prefix;
        $id .= '-';
        $id .= str_pad($this->alloted_id, 20, "0", STR_PAD_LEFT);
        return $id;
    }
}

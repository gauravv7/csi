<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{	

	protected $fillable = ['member_id', 'country_code', 'std_code', 'landline', 'mobile'];

    public function country()	{
    	return $this->hasOne('App\Country', 'alpha3_code', 'country_code');
    }


    public function member()	{
    	return $this->hasOne('App\Member', 'id', 'member_id');
    }


    public function state()	{
    	return $this->hasOne('App\State', 'state_code', 'state_code');
    }

}

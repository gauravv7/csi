<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['alpha2_code', 'alpha3_code', 'name', 'dial_code'];

    
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipType extends Model
{
    protected $fillable = ['membership_id', 'prefix', 'type'];
}


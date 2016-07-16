<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CsiRequest extends Model
{
	protected $fillable = ['requested_by', 'request_type', 'status'];


	public function scopeMemberships($query) {
		$query->where('request_type', 1);
	}

	public function scopeStudentBranch($query) {
		$query->where('request_type', 2);
	}

	public function scopeIsPending($query) {
		$query->where('status_code', -1);
	}

	public function scopeIsRejected($query) {
		$query->where('status_code', 0);
	}

	public function scopeIsCompleted($query) {
		$query->where('status_code', 1);
	}

	public function requestedBy() {
		return $this->hasOne('App\Member', 'id', 'requested_by');
	}

	public function requestType() {
		return $this->hasOne('App\RequestType', 'id', 'request_type');
	}
}

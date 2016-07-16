<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Member;
use Illuminate\Support\Facades\Auth;

class UpdateContactRequest extends Request
{

    private $individual_contact = [
        'email1' => 'required|email',
        'email2' => 'email',
        'std' => 'numeric',
        'phone' => 'numeric',
        'country-code' => 'required|numeric',
        'mobile' => 'required|numeric'
    ];

    private $contact = [
        'email1' => 'required|email',
        'email2' => 'email',
        'std' => 'required|numeric',
        'phone' => 'required|numeric'
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->route('id')!=null){
            $member = Member::find($this->route('id'));
        } else if(!Auth::user()->guest()){
            $member = Member::find(Auth::user()->user()->id);
        }

        if( ($member->membership->type == 'institutional') ){
            return $this->contact;
        } else if( ($member->membership->type == 'individual') ){
            return $this->individual_contact;
        }
    }
}

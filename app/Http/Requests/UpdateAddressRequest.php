<?php

namespace App\Http\Requests;

use App\Address;
use App\Http\Requests\Request;

class UpdateAddressRequest extends Request
{
    
    private $student_address = [
        'country' => 'required',
        'state' => 'required',
        'stud_branch' => 'required',
        'address' => 'required|string',
        'city' => 'required|string',
        'pincode' => 'required|numeric'
    ];

    private $address = [
        'country' => 'required',
        'state' => 'required',
        'address' => 'required|string',
        'city' => 'required|string',
        'pincode' => 'required|numeric'
    ];

    private $address_with_chapter = [
        'country' => 'required',
        'state' => 'required',
        'chapter' => 'required',
        'address' => 'required|string',
        'city' => 'required|string',
        'pincode' => 'required|numeric'
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
        $address = Address::find($this->route('id'));

        if($address->type->type == "mailing address") {
            if( ($address->member->membership->type == 'institutional') && ($address->member->getMembership->membershipType->type == 'academic') ){
                return $this->address_with_chapter;
            } else if( ($address->member->membership->type == 'institutional') && ($address->member->getMembership->membershipType->type == 'non-academic') ){
                return $this->address_with_chapter;
            } else if( ($address->member->membership->type == 'individual') && ($address->member->getMembership->membershipType->type == 'student') ){
                return $this->student_address;
            } else if( ($address->member->membership->type == 'individual') && ($address->member->getMembership->membershipType->type == 'professional') ){
                return $this->address_with_chapter;
            }
        } else{
            return $this->address;
        }
        
    }
}

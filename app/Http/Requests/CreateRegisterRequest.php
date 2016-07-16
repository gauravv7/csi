<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateRegisterRequest extends Request
{
    
    private $institutional_academic = [
        'institution_type' => 'required',
        'nameOfInstitution' => 'required|string'
    ];
    private $institutional_non_academic = [
        'nameOfInstitution' => 'required|string'
    ];

    private $individual = [
        'salutation' => 'required',
        'fname' => 'required|string',
        'mname' => 'string',
        'lname' => 'string',
        'card_name' => 'required|string',
        'dob' => 'required|date_format:d/m/Y',
        'gender' => 'required'
    ];

    private $student_address = [
        'country' => 'required|not_in:invalid',
        'state' => 'required|not_in:invalid',
        'stud_branch' => 'required|not_in:invalid',
        'address' => 'required|string',
        'city' => 'required|string',
        'pincode' => 'required|numeric'
    ];

    private $address = [
        'country' => 'required|not_in:invalid',
        'state' => 'required|not_in:invalid',
        'chapter' => 'required|not_in:invalid',
        'address' => 'required|string',
        'city' => 'required|string',
        'pincode' => 'required|numeric'
    ];

    private $individual_contact = [
        'email1' => 'required|email|unique:members,email',
        'email2' => 'email|unique:members,email_extra',
        'std' => 'numeric',
        'phone' => 'numeric',
        'country-code' => 'required|numeric',
        'mobile' => 'required|numeric|digits:10'
    ];

    private $contact = [
        'email1' => 'required|email|unique:members,email',
        'email2' => 'email|unique:members,email_extra',
        'std' => 'required|numeric',
        'phone' => 'required|numeric'
    ];
    

    private $student_details = [
        'college' => 'required|string',
        'course' => 'required|string',
        'cbranch' => 'required|string',
        'cduration' => 'required|string',
        'student_id' => 'required|mimes:jpg,jpeg,bmp,png,pdf',
    ];

    private $professional_details = [
        'organisation' => 'required|string',
        'designation' => 'required|string',
        'employee_id' => 'required|mimes:jpg,jpeg,png,bmp,pdf',
    ];

    private $details_of_head = [
        'salutation' => 'required',
        'headName' => 'required|string',
        'headDesignation' => 'required|string',
        'headEmail' => 'required|email',
        'country-code' => 'required|numeric',
        'mobile' => 'required|numeric'
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
        $validation = array();

        if(($this->route('entity') == 'institution-academic') ) {
            $validation = array_merge( $this->institutional_academic, $this->details_of_head);
        }
        if ( ($this->route('entity') == 'institution-non-academic')) {
            $validation = array_merge( $this->institutional_non_academic, $this->details_of_head);
        }
        if ( ($this->route('entity') == 'individual-student')) {
            $validation = array_merge( $this->individual, $this->student_details, $this->student_address);
        }
        if ( ($this->route('entity') == 'individual-professional')) {
            $validation = array_merge( $this->individual, $this->professional_details, $this->address);
        }



        if(($this->route('entity') == 'institution-academic') || ($this->route('entity') == 'institution-non-academic')) {
            $validation = array_merge( $validation, $this->address, $this->contact);
        } else if(($this->route('entity') == 'individual-student') || ($this->route('entity') == 'individual-professional')) {
            $validation = array_merge( $validation, $this->individual_contact);
        }
        
        return $validation;
    }


    public function messages() {
        return [
            'email1.required' => 'The primary email is required',
            'email2.required' => 'The secondary email is required',
            'email1.unique' => 'The primary email given already exists, please try with a different one',
            'email2.unique' => 'The secondary email given already exists, please try with a different one'
        ];
    }
}

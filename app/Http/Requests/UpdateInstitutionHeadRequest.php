<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateInstitutionHeadRequest extends Request
{
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
        return $this->details_of_head;
    }
}

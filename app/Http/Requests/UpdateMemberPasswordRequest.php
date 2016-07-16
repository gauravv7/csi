<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateMemberPasswordRequest extends Request
{
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
        return [
            'oldpswd' => 'required|String',
            'pswd'  => 'required|String',
            'rpswd' => 'required|String|same:pswd',
        ];
    }

    public function messages(){
        return [
            'oldpswd.required' => 'Old Password is required',
            'pswd.required' => 'Password is required',
            'rpswd.required' => 'Password is required',
            'rpswd.same' => 'Password donot match, please retype the password',
        ];
    }
}

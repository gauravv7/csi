<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateNarrationRequest extends Request
{
    private $data = [
        'branch'        => 'required|string',
        'bank'          => 'required|string',
        'amountPaid'    => 'required|numeric',
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
        return $this->data;
    }
}

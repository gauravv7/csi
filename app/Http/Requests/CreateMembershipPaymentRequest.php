<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateMembershipPaymentRequest extends Request
{
    

    private $payment = [
        'paymentMode' => 'required',
        'tno' => 'required',
        'drawn' => 'required|date_format:d/m/Y',
        'bank' => 'required|string',
        'branch' => 'required|string',
        'paymentReciept' => 'required|mimes:jpg,jpeg,bmp,png,pdf',
        'amountPaid' => 'required|numeric'
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
        return $this->payment;
    }
}

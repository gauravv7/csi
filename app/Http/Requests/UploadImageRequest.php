<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UploadImageRequest extends Request
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
            'upload_image' => 'required|image'
        ];
    }

    public function messages()
    {
        return [
            'upload_image.required' => 'Please upload an image',
            'upload_image.image' => 'The uploaded file is not an image',
        ];
    }
}

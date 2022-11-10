<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
{


    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'body' => 'required',
        ];
    }
    
    public function messages()
    {
        return [
            'body.required'  => trans('validation.required'),
        ];
    }
}

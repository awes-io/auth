<?php

namespace AwesIO\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TwoFactorStoreRequest extends FormRequest
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
            'phone' => 'required',
            'dial_code' => 'required|exists:countries,dial_code',
        ];
    }
}

<?php

namespace AwesIO\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use AwesIO\Auth\Rules\ValidPhone;

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
            'phone' => ['required', new ValidPhone],
            // 'dial_code' => 'required|exists:countries,dial_code',
        ];
    }
}

<?php

namespace AwesIO\Auth\Requests;

use AwesIO\Auth\Rules\ValidTwoFactorToken;
use Illuminate\Foundation\Http\FormRequest;
use AwesIO\Auth\Services\Contracts\TwoFactor;

class TwoFactorVerifyRequest extends FormRequest
{
    /**
     * Two factor service
     *
     * @var \AwesIO\Auth\Services\Contracts\TwoFactor
     */
    protected $twoFactor;

    public function __construct(TwoFactor $twoFactor)
    {
        $this->twoFactor = $twoFactor;
    }

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
        if ($data = session('two_factor')) {
            $this->setUserResolver($this->userResolver($data));
        }

        return [
            'token' => [
                'required',
                new ValidTwoFactorToken($this->user(), $this->twoFactor)
            ],
        ];
    }

    /**
     * Get custom user resolver
     *
     * @param object $data
     * @return \Closure
     */
    protected function userResolver($data)
    {
        return function () use ($data) {
            return getModelForGuard(config('auth.defaults.guard'))::find($data->user_id);
        };
    }
}

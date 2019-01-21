<?php

namespace AwesIO\Auth\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;
use AwesIO\Auth\Services\Contracts\TwoFactor;

class ValidTwoFactorToken implements Rule
{
    protected $user;

    protected $twoFactor;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(User $user, TwoFactor $twoFactor)
    {
        $this->user = $user;

        $this->twoFactor = $twoFactor;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->twoFactor->verifyToken($this->user, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid two factor token';
    }
}

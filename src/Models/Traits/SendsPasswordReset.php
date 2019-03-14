<?php

namespace AwesIO\Auth\Models\Traits;

use AwesIO\Mail\Mail\ResetPassword;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

trait SendsPasswordReset
{
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        ResetPasswordNotification::toMailUsing(
            
            function ($notifiable, $token) {

                $url = url(route('password.reset', $token));

                return (new ResetPassword($token, $url))
                    ->to($notifiable->email);
            }
        );
        $this->notify(new ResetPasswordNotification($token));
    }
}

<?php

namespace AwesIO\Auth\Models\Traits;

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
        if ($mailable = config('awesio-auth.mailables.reset_password')) {

            ResetPasswordNotification::toMailUsing(
                
                function ($notifiable, $token) use ($mailable) {

                    $url = url(route('password.reset', $token));

                    return (new $mailable($url))
                        ->to($notifiable->email);
                }
            );
        }
        $this->notify(new ResetPasswordNotification($token));
    }
}

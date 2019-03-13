<?php

namespace AwesIO\Auth\Models\Traits;

use Illuminate\Support\Facades\URL;
use AwesIO\Mail\Mail\EmailConfirmation;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Notifications\VerifyEmail;

trait SendsEmailVerification
{
    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        VerifyEmail::toMailUsing(
            function ($notifiable) {

                $code = $this->generateVerificationCode();

                $url = URL::temporarySignedRoute(
                    'verification.verify', 
                    $expire = now()->addMinutes(60), 
                    ['id' => $notifiable->getKey()]
                );

                Session::put(
                    'email_verification', 
                    ['code' => $code, 'expire' => $expire]
                );

                return (new EmailConfirmation($code, $url))
                    ->to($notifiable->email);
            }
        );
        $this->notify(new VerifyEmail);
    }

    /**
     * Generate six digits verification code
     *
     * @throws \Exception
     */
    public function generateVerificationCode()
    {
        $code = '';

        for ($i = 0; $i < 6; $i++) {
            $code .= random_int(0, 9);
        }

        return $code;
    }
}

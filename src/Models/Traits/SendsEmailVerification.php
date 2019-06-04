<?php

namespace AwesIO\Auth\Models\Traits;

use Illuminate\Support\Facades\URL;
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
        $code = $this->generateVerificationCode();

        $expire = now()->addMinutes(60);

        if ($mailable = config('awesio-auth.mailables.email_verification')) {

            VerifyEmail::toMailUsing(
                function ($notifiable) use ($mailable, $code, $expire) {
    
                    $url = URL::temporarySignedRoute(
                        'verification.verify', 
                        $expire, 
                        ['id' => $notifiable->getKey()]
                    );
    
                    return (new $mailable($code, $url))
                        ->to($notifiable->email);
                }
            );
        }
        Session::put(
            'email_verification', 
            ['code' => $code, 'expire' => $expire]
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

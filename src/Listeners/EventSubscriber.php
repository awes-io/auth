<?php

namespace AwesIO\Auth\Listeners;

use AwesIO\Auth\Facades\Auth as AwesAuth;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class EventSubscriber
{
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Registered', 
            static::class.'@handleRegistered'
        );
    }

    public function handleRegistered($event)
    {
        if (AwesAuth::isEmailVerificationEnabled() 
            && ! $event->user->hasVerifiedEmail()) {
                
            $event->user->sendEmailVerificationNotification();
        }
    }
}
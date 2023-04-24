<?php

namespace App\Api\v1\Listeners;

use App\Api\v1\Emails\NewUserPasswordRequested as NewUserPasswordRequestedEmail;
use App\Api\v1\Events\NewUserPasswordRequested as NewUserPasswordRequestedEvent;
use Illuminate\Support\Facades\Mail;

class NewUserPasswordRequested
{
    /**
     * Handle the event.
     *
     * @param  NewUserPasswordRequestedEvent  $event
     * @return void
     */
    public function handle(NewUserPasswordRequestedEvent $event)
    {
        Mail::to($event->user->email)
            ->send(new NewUserPasswordRequestedEmail($event->user, $event->token));
    }
}

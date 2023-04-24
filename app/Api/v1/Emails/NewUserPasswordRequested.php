<?php

namespace App\Api\v1\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Lang;

class NewUserPasswordRequested extends Mailable
{
    use Queueable;
    use SerializesModels;

    private $email;

    private $user;

    private $token;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $token
     */

    public function __construct($user, $token)
    {
        $this->email = $user->email;
        $this->subject = Lang::get('Reset Password Notification');
        $this->user = $user;
        $this->token = $token;
    }

    public function build()
    {
        $url = rtrim(config('auth.password_reset_url'), '/') . '?token=' . $this->token . '&email=' . $this->email;

        return $this->view('mails.user_reset_password', [
            'email' => $this->email,
            'url' => $url,
        ])->from(config('mail.from.address'), config('mail.from.name'));
    }
}

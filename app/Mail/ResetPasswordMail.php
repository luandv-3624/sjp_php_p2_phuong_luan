<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ResetPasswordMail extends Mailable
{
    public string $token;
    public string $email;

    public function __construct(string $email, string $token)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function build()
    {
        $url = url('/reset-password?email=' .$this->email. '&token=' . $this->token);

        return $this->subject(__('auth.reset_password_subject'))
            ->view('emails.reset-password')
            ->with(['url' => $url]);
    }
}

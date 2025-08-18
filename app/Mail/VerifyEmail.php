<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $user;
    public $token;


    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function build()
    {
        $verifyUrl = url("/api/auth/verify?token={$this->token}");

        return $this->subject(__('auth.verify_email_subject'))
                    ->view('emails.verify')
                    ->with([
                        'name' => $this->user->name,
                        'verifyUrl' => $verifyUrl,
                    ]);
    }
}

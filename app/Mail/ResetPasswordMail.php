<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

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
        $domain = config('app.url');
        $url = url("{$domain}/reset-password?email=" . $this->email . '&token=' . $this->token);

        $html = view('emails.reset-password', ['url' => $url])->render();

        $css = file_get_contents(resource_path('css/email.css'));

        $inliner = new CssToInlineStyles();
        $htmlWithInlineCss = $inliner->convert($html, $css);

        return $this->subject(__('auth.reset_password_subject'))
                    ->html($htmlWithInlineCss);
    }
}

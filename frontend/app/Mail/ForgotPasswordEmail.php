<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected $forgotPasswordToken;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($forgotPasswordToken)
    {
        $this->forgotPasswordToken = $forgotPasswordToken;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $baseUrl = url()->route('resetpassword');
        $forgotPasswordLink = $baseUrl.'?token='.$this->forgotPasswordToken;

        return $this->view('emails.forgotpassword')
            ->with([
                'forgotpasswordlink' => $forgotPasswordLink,
            ]);
    }
}

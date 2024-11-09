<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;

class ResetPasswordMail extends Mailable
{
public $otp;

public function __construct($otp)
{
$this->otp = $otp;
}

public function build()
{
    return $this->subject('Your OTP for Password Reset')
                ->view('emails.reset_password'); // Pastikan view ini ada
}

}
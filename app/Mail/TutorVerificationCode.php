<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TutorVerificationCode extends Mailable
{
    use Queueable, SerializesModels;

    public $tutorDetail;
    public $verificationCode;

    /**
     * Create a new message instance.
     *
     * @param $tutorDetail
     * @param $verificationCode
     */
    public function __construct($tutorDetail, $verificationCode)
    {
        $this->tutorDetail = $tutorDetail;
        $this->verificationCode = $verificationCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Verification Code: ' . $this->verificationCode)
            ->view('emails.tutor_verification_code');
    }
}

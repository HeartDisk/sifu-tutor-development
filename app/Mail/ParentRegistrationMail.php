<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ParentRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tutorDetail;

    /**
     * Create a new message instance.
     *
     * @param $tutorDetail
     */
    public function __construct()
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Parent Registration')
            ->view('emails.parent_registration');
    }
}

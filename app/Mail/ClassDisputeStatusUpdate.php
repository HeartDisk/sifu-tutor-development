<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClassDisputeStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $jobTicketData;

    /**
     * Create a new message instance.
     *
     * @param $jobTicketData
     * @param $attachmentPath
     */
    public function __construct($jobTicketData)
    {
        $this->jobTicketData = $jobTicketData;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('emails.class_dispute_status_update')
            ->subject("Class Dispute Status Update")
            ->from('tutor@sifu.qurangeek.com', 'SifuTutor');

        return $email;
    }
}

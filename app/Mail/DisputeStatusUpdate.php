<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DisputeStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $jobTicketData;

    /**
     * Create a new message instance.
     *
     * @param  $jobTicketData
     * @return void
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
        return $this->view('emails.disputeStatusUpdate')
            ->subject('Class Dispute Status Update')
            ->from('tutor@sifu.qurangeek.com');
    }
}

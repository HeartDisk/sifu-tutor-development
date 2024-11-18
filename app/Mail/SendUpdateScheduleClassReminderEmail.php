<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendUpdateScheduleClassReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailContent;

    /**
     * Create a new message instance.
     *
     * @param string $emailContent
     */
    public function __construct($emailContent)
    {
        $this->emailContent = nl2br(e($emailContent)); // Convert newlines to <br> for HTML rendering
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Update all your class schedules before the 1st of the following month.')
            ->from('tutor@sifu.qurangeek.com', 'SifuTutor')
            ->view('emails.update_schedule_reminder');
    }
}

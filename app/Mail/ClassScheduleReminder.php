<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClassScheduleReminder extends Mailable
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
        $this->emailContent = nl2br($emailContent); // Convert newlines to <br> for HTML rendering
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Create Class Schedule Reminder')
            ->from('tutor@sifu.qurangeek.com')
            ->view('emails.class_schedule_reminder');
    }
}

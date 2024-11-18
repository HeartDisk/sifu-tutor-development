<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ParentProgressReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $tutorName;
    public $parentName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($studentName, $tutorName, $parentName)
    {
        $this->studentName = $studentName;
        $this->tutorName = $tutorName;
        $this->parentName = $parentName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.progress_report')
                    ->subject("Progress Report for {$this->studentName}")
                    ->with([
                        'studentName' => $this->studentName,
                        'tutorName' => $this->tutorName,
                        'parentName' => $this->parentName,
                    ]);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EvaluationReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $parentName;
    public $tutorName;
    public $studentName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($parentName, $tutorName, $studentName)
    {
        $this->parentName = $parentName;
        $this->tutorName = $tutorName;
        $this->studentName = $studentName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.evaluation_report')
                    ->subject("Evaluation Report for {$this->studentName}")
                    ->with([
                        'parentName' => $this->parentName,
                        'tutorName' => $this->tutorName,
                        'studentName' => $this->studentName,
                    ]);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ParentApprovalEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $parentName;
    public $studentName;
    public $subjectName;
    public $schedule;
    public $specialRequirement;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($parentName, $studentName, $subjectName, $schedule, $specialRequirement)
    {
        $this->parentName = $parentName;
        $this->studentName = $studentName;
        $this->subjectName = $subjectName;
        $this->schedule = $schedule;
        $this->specialRequirement = $specialRequirement;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.parent_approval')
                    ->subject("Tutor Confirmed for {$this->studentName}")
                    ->with([
                        'parentName' => $this->parentName,
                        'studentName' => $this->studentName,
                        'subjectName' => $this->subjectName,
                        'schedule' => $this->schedule,
                        'specialRequirement' => $this->specialRequirement,
                    ]);
    }
}

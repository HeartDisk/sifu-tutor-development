<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AttendanceReportMailAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $attendedRecord;
    public $studentName;
    public $subjectName;
    public $agreePath;
    public $disagreePath;
    public $total_time_attended;

    /**
     * Create a new message instance.
     */
    public function __construct($attendedRecord, $studentName, $subjectName, $agreePath, $disagreePath, $total_time_attended)
    {
        $this->attendedRecord = $attendedRecord;
        $this->studentName = $studentName;
        $this->subjectName = $subjectName;
        $this->agreePath = $agreePath;
        $this->disagreePath = $disagreePath;
        $this->total_time_attended = $total_time_attended;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        
        return $this->subject('Attendance Report at: ' . date('Y-m-d H:i:s'))
                    ->view('emails.attendance_report_admin');
    }
}
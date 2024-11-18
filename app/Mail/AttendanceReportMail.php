<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AttendanceReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $attendedRecord;
    public $studentName;
    public $subjectName;
    public $tutors;
    public $total_time_attended;
    public $agreePath;
    public $disagreePath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    // public function __construct($attendedRecord, $studentName, $subjectName)
    // {
    //     $this->attendedRecord = $attendedRecord;
    //     $this->studentName = $studentName;
    //     $this->subjectName = $subjectName;
    //     $this->total_time_attended = number_format($attendedRecord->totalTime, 2);
    //     $this->agreePath = url("/agreeAttendance/" . $attendedRecord->id);
    //     $this->disagreePath = url("/disputeAttendance/" . $attendedRecord->id);
    // }

    public function __construct($attendedRecord, $studentName, $subjectName, $tutors)
    {
        $this->attendedRecord = $attendedRecord;
        $this->studentName = $studentName;
        $this->subjectName = $subjectName;
        $this->tutors = $tutors;
    
        // Initialize default total time attended
        $this->total_time_attended = '0.00';
    
        // Check if totalTime is set and properly formatted
        if (isset($attendedRecord->totalTime) && preg_match('/^\d{2}:\d{2}:\d{2}$/', $attendedRecord->totalTime)) {
            // Convert totalTime to total hours or minutes
            list($hours, $minutes, $seconds) = explode(':', $attendedRecord->totalTime);
            $totalHours = $hours + ($minutes / 60) + ($seconds / 3600); // Convert to total hours
        
            // Format the numeric value
            $this->total_time_attended = number_format($totalHours, 2); 
        }
    
        $this->agreePath = url("/agreeAttendance/" . $attendedRecord->id);
        $this->disagreePath = url("/disputeAttendance/" . $attendedRecord->id);
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Attendance Report at: ' . now()->format('Y-m-d H:i:s'))
            ->from('tutor@sifu.qurangeek.com')
            ->view('emails.attendance_report');
    }
}

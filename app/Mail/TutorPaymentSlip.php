<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class TutorPaymentSlip extends Mailable
{
    use Queueable, SerializesModels;

    public $paidClasses;
    public $tutor;
    public $tutor_payment;
    public $additionals;
    public $deductions;
    public $pdfPath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($paidClasses, $tutor, $tutor_payment, $additionals, $deductions)
    {
        $this->paidClasses = $paidClasses;
        $this->tutor = $tutor;
        $this->tutor_payment = $tutor_payment;
        $this->additionals = $additionals;
        $this->deductions = $deductions;

        // Generate PDF and save it
        $this->pdfPath = public_path('tutorPaymentSlipPDF') . "/tutor-Payment-Slip-" . $tutor_payment->id . ".pdf";

        if (!file_exists($this->pdfPath)) {
            $pdf = PDF::loadView('tutor.tutorPaymentSlipPDF', [
                'paidClasses' => $this->paidClasses,
                'tutor' => $this->tutor,
                'tutor_payment' => $this->tutor_payment,
                'additionals' => $this->additionals,
                'deductions' => $this->deductions,
            ]);
            $pdf->save($this->pdfPath);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.tutorPaymentSlip')
            ->subject('Tutor Payment Slip')
            ->from('tutor@sifu.qurangeek.com')
            ->attach($this->pdfPath, [
                'as' => 'Tutor-Payment-Slip-' . $this->tutor_payment->id . '.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}

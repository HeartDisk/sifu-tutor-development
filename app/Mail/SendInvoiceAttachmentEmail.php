<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class SendInvoiceAttachmentEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
   public function build()
    {
        // Define the path to the PDF in the public folder
        $pdfPath = public_path('invoicePDF/Invoice-10.pdf');

        return $this->view('emails.attachmentEmail') // your email view here
                    ->subject('Your Subject Here')
                    ->attach($pdfPath, [
                        'as' => 'filename.pdf', // This is the name the recipient will see
                        'mime' => 'application/pdf',
                    ]);
    }
}

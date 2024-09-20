<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectContent;
    public $nameContent;


    /**
     * Create a new message instance.
     */
    public function __construct($subject, $name)
    {
        $this->subjectContent = $subject;
        $this->nameContent = $name;
    }

 
 
    public function build()
    {
        return $this->view('emails.verifyEmail')
                    ->with([
                        'subjectContent' => $this->subjectContent,
                        'nameContent' => $this->nameContent,
                    ]);
    }
}

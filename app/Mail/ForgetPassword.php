<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $nameContent;
    public $subjectContent;
    public $otpContent;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $subject,$otp )
    {
        $this->nameContent = $name;
        $this->subjectContent = $subject;
        $this->otpContent = $otp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.forgetPassword')
                    ->with([
                        'nameContent' => $this->nameContent,
                        'subjectContent' => $this->subjectContent,
                        'otpContent' => $this->otpContent,
                    ]);
    }
}

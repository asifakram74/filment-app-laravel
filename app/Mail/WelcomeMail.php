<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $messageContent;
    public $subjectContent;
    public $nameContent;

    /**
     * Create a new message instance.
     */
    public function __construct($message, $subject, $name)
    {
        $this->messageContent = $message;
        $this->subjectContent = $subject;
        $this->nameContent = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.welcome')
                    ->with([
                        'messageContent' => $this->messageContent,
                        'subjectContent' => $this->subjectContent,
                        'nameContent' => $this->nameContent,
                    ]);
    }
}

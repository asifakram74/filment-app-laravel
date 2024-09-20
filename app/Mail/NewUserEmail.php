<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
 
class NewUserEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $messageContent;
    public $subjectContent;
    public $nameContent;
    public $emailContent;

    /**
     * Create a new message instance.
     */
    public function __construct($message, $subject, $name, $email)
    {
        $this->messageContent = $message;
        $this->subjectContent = $subject;
        $this->nameContent = $name;
        $this->emailContent = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.newUserNotifcation')
                    ->with([
                        'messageContent' => $this->messageContent,
                        'subjectContent' => $this->subjectContent,
                        'nameContent' => $this->nameContent,
                        'emailContent' => $this->emailContent,
                    ]);
    }
}

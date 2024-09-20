<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingStore extends Mailable
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
        return $this->view('emails.bookingStore')
                    ->with([
                        'messageContent' => $this->messageContent,
                        'subjectContent' => $this->subjectContent,
                        'nameContent' => $this->nameContent,
                    ]);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HRMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $details;
    public $attachments;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details,  $attachments = [])
    {
        $this->details = $details;
        $this->attachments = is_array($attachments) ? $attachments : [];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('emails.hrmail')
                    ->subject($this->details['subject'])
                    ->with('content', $this->details['content']);

        // Debugging: Log attachments
        \Log::info('Attachments:', $this->attachments);

        // Attach files if there are any
        foreach ($this->attachments as $attachment) {
            if (isset($attachment['path'], $attachment['name'], $attachment['mime'])) {
                $email->attach($attachment['path'], [
                    'as' => $attachment['name'],
                    'mime' => $attachment['mime'],
                ]);
            }
        }

        return $email;
    }

}

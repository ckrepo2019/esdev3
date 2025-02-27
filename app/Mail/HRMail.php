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
    protected $fromEmail;
    protected $fromName;
    protected $filePath; // Path to the file to be attached
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $fromEmail, $fromName, $filePath = null)
    {
        $this->details = $details;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
        $this->filePath = $filePath; // Store the file path
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    // public function build()
    // {
    //     return $this->view('mail.hr')->with('data', $this->details);
    // }
    public function build()
    {
        $email = $this->from($this->fromEmail, $this->fromName)
            ->subject('Leave Application: ' . $this->details['subject'])
            ->view('mail.hr')
            ->with('details', $this->details);

        // Attach the file if provided
        if ($this->filePath && file_exists($this->filePath)) {
            $email->attach($this->filePath);
        }

        return $email;
    }


}

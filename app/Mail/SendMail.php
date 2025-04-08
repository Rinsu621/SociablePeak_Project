<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $action_link;
    public $body;
    public $two_factor_code;
    /**
     * Create a new message instance.
     */
    // public function __construct($action_link, $body)
    // {
    //     $this->action_link=$action_link;
    //     $this->body=$body;
    // }
    public function __construct($action_link = null, $body = null, $two_factor_code = null)
    {
        $this->action_link = $action_link;
        $this->body = $body;
        $this->two_factor_code = $two_factor_code; // Initialize the 2FA code
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "SociablePeak",
        );
    }



    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {
    //     return [];
    // }

    public function build()
    {


        $view = 'auth.email-forget'; // Default view, adjust if you want a different one for 2FA

        // If a 2FA code is provided, use a different view or pass it to the existing view
        if ($this->two_factor_code) {
            $view = 'emails.two-factor'; // Create this view for 2FA emails
        }

        return $this->from('sociablepeak@gmail.com', 'SociablePeak')
                    ->subject($this->two_factor_code ? 'Your 2FA Code' : 'Reset Password')
                    ->view($view)
                    ->with([
                        'action_link' => $this->action_link,
                        'body' => $this->body,
                        'two_factor_code' => $this->two_factor_code, // Pass the 2FA code to the view
                    ]);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailContactUs extends Mailable
{
    use Queueable, SerializesModels;
    protected $contact;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contact)
    {
        $this->contact = $contact;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from( config('global.support_email_id'), config('global.website_title') )
                    ->markdown('site.emails.contact.contactemail')
                    ->subject('Successful submission of enquiry form')
                    ->with(['data' => $this->contact]);
    }
}
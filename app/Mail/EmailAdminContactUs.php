<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailAdminContactUs extends Mailable
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
        //return $this->from( $this->contact['email'], $this->contact['name'] )
        return $this->from( $this->contact['email'], config('global.website_title') )
                    ->markdown('site.emails.contact.contactemailtoadmin')
                    ->subject('Contact Us')
                    ->with(['data' => $this->contact]);
    }
}
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailPromotionalFromAdmin extends Mailable
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
        return $this->from(config('global.admin_email_id'), config('global.website_title'))
                    ->markdown('site.emails.promotional.promotionalemailfromadmin')
                    ->subject(config('global.website_title_camel_case').'-'.'Giftbasket Promotional')
                    ->with(['data' => $this->contact]);

               
    }
}
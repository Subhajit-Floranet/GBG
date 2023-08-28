<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailPaypalResponse extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('global.admin_email_id'))
                    ->subject(config('global.website_title_camel_case').'—'.'Thank you for shopping with us!')
                    ->markdown('site.emails.order.paypal_place_order')->with(['data'=>$this->user]);
    }
}
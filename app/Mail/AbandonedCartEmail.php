<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AbandonedCartEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected $user_data;
    protected $cart_detail_array;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user_data, $cart_detail_array)
    {
        $this->user_data = $user_data;
        $this->cart_detail_array = $cart_detail_array;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->from( $this->contact['email'], $this->contact['name'] )
        return $this->from( config('global.admin_email_id'), config('global.website_title') )
                    ->subject(config('global.website_title_camel_case').'—'.'You left something special behind!')
                    ->markdown('site.emails.order.abandoned')->with(['data'=>$this->user_data, 'order_data'=>$this->cart_detail_array]);
    }
}
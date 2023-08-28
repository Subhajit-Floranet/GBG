<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailPlaceOrder extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;
    protected $order_data;
    protected $main_order_data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$order_data,$main_order_data)
    {
        $this->user = $user;
        $this->order_data = $order_data;
        $this->main_order_data = $main_order_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		return $this->from( config('global.admin_email_id'), config('global.website_title') )
                    ->subject(config('global.website_title_camel_case').'—'.'Thank you for shopping with us!')
                    ->markdown('site.emails.order.place_order')->with(['data'=>$this->user,'order_data'=>$this->order_data,'main_order_data'=>$this->main_order_data]);
    }
}
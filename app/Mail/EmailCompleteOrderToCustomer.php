<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailCompleteOrderToCustomer extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mail_data)
    {
        $this->user = $mail_data;
    }

    /**
     * Build the message.
     * 
     * @return $this
     */
    public function build()
    {
        /*return $this->from(config('global.admin_email_id'))*/
        return $this->from(config('global.admin_email_id'), config('global.website_title'))
                    ->subject(config('global.website_title_camel_case').'-'.'Your order is completed')
                    ->markdown('site.emails.order.order_complete_email_to_customer')->with(['data'=>$this->user]);
    }
}
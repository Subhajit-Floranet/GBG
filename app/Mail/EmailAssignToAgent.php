<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailAssignToAgent extends Mailable
{
    use Queueable, SerializesModels;
    protected $ref;
    protected $order_data;
    protected $main_order_data;
    protected $data_item_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ref,$order_data,$main_order_data,$data_item_id)
    {
        $this->ref = $ref;
        $this->order_data = $order_data;
        $this->main_order_data = $main_order_data;
        $this->data_item_id = $data_item_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		return $this->from( config('global.admin_email_id') )
                    ->subject('Our Order No :- '.$this->ref )
                    ->markdown('site.emails.order.assign_order')->with(['data'=>$this->ref,'order_data'=>$this->order_data,'main_order_data'=>$this->main_order_data, 'data_item_id'=>$this->data_item_id]);
    }
}
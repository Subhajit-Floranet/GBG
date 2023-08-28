<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordUpdate extends Mailable
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
        $this->user=$user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       
        // return $this->view('site.emails.user.passwordupdate')->subject(config('global.website_title_camel_case').'—'.'You updated your password')->with(['data'=>$this->user]);

        return $this->from( config('global.admin_email_id'), config('global.website_title') )
                    ->subject(config('global.website_title_camel_case').'—'.'You updated your password')
                    ->markdown('site.emails.user.passwordupdate')->with(['data'=>$this->user]); 
    }
}
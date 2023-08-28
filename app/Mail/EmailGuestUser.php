<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailGuestUser extends Mailable
{
    use Queueable, SerializesModels;
    protected $guest_user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($guest_user)
    {
        $this->guest_user = $guest_user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from( $this->guest_user['email'], config('global.website_title') )
                    ->markdown('site.emails.user.guestuseremail')
                    ->subject('Registration Email')
                    ->with(['data' => $this->guest_user]);
    }
}
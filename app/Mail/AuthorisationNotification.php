<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AuthorisationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $authorisations;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\User $user
     * @param \Illuminate\Support\Collection $authorisations
     */
    public function __construct($user, $authorisations)
    {
        $this->user = $user;
        $this->authorisations = $authorisations;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Authorisation Details')
                    ->view('emails.authorisation_notification')
                    ->with([
                        'user' => $this->user,
                        'authorisations' => $this->authorisations,
                    ]);
    }
}

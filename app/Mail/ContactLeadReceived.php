<?php

namespace App\Mail;

use App\Models\ContactLead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactLeadReceived extends Mailable
{
    use Queueable, SerializesModels;

    public ContactLead $lead;

    public function __construct(ContactLead $lead)
    {
        $this->lead = $lead;
    }

    public function build(): self
    {
        return $this->subject('New Contact Lead: ' . $this->lead->subject)
            ->view('emails.contact.received')
            ->with(['lead' => $this->lead]);
    }
}



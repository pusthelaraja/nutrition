<?php

namespace App\Mail;

use App\Models\ContactLead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactLeadConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public ContactLead $lead;

    public function __construct(ContactLead $lead)
    {
        $this->lead = $lead;
    }

    public function build(): self
    {
        return $this->subject('We received your message - ' . config('app.name'))
            ->view('emails.contact.confirmation')
            ->with(['lead' => $this->lead]);
    }
}



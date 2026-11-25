<?php

namespace App\Mail;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PlanExpiryMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Company $company, public int $daysBefore) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: '[Doc2Due] Your plan expires in '.$this->daysBefore.' days');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.plan-expiry');
    }
}

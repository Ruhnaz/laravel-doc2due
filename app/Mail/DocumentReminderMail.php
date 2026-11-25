<?php

namespace App\Mail;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Document $document, public int $daysBefore) {}

    public function envelope(): Envelope
    {
        $subject = $this->daysBefore === 0
            ? '[Doc2Due] '.$this->document->title.' expires today'
            : '[Doc2Due] '.$this->document->title.' expires in '.$this->daysBefore.' days';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'mail.document-reminder');
    }
}

<?php

namespace App\Mail;

use App\Models\DailyReflection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyReflectionMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public DailyReflection $reflection;

    public function __construct(DailyReflection $reflection)
    {
        $this->reflection = $reflection;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Today's Reflection — The Muhsinat Club",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.daily-reflection',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

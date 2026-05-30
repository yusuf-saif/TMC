<?php

namespace App\Mail;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnnouncementMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Announcement $announcement;

    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'TMC Announcement — '.$this->announcement->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.announcement',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

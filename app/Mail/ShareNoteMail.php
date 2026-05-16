<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Note;


class ShareNoteMail extends Mailable
{
    use Queueable, SerializesModels;

    public Note $note;
    public string $senderName;
    public string $noteUrl;

    public function __construct(    
        Note $note,
        string $senderName,
        string $noteUrl,
    ) {
        $this->note = $note;
        $this->senderName = $senderName;
        $this->noteUrl = $noteUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Share Note Mail'. $this->note->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.share-note',
            with: [
                'note' => $this->note,
                'senderName' => $this->senderName,
                'noteUrl' => $this->noteUrl,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

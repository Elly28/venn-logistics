<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyVehicleReportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public array $reportData) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Weekly Fleet Usage & Inspection Report',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.fleet.weekly-report',
        );
    }
}

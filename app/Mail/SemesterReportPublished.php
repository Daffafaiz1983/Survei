<?php

namespace App\Mail;

use App\Models\SemesterReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SemesterReportPublished extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public SemesterReport $semesterReport;

    /**
     * Create a new message instance.
     */
    public function __construct(SemesterReport $semesterReport)
    {
        $this->semesterReport = $semesterReport;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Laporan Semester ' . $this->semesterReport->formatted_semester . ' Telah Dipublikasi',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.semester-report-published',
            with: [
                'semesterReport' => $this->semesterReport,
                'url' => route('admin.semester-reports.show', $this->semesterReport),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

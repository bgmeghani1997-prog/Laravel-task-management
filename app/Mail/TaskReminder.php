<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $tasks;
    public $user;

    /* Create a new message instance */
    public function __construct($user, $tasks)
    {
        $this->user = $user;
        $this->tasks = $tasks;
    }

    /* Get the message envelope */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Task Reminder - Tasks Due Tomorrow',
        );
    }

    /* Get the message content definition */
    public function content(): Content
    {
        return new Content(
            view: 'emails.task_reminder',
            with: [
                'user' => $this->user,
                'tasks' => $this->tasks,
            ],
        );
    }
    public function attachments(): array
    {
        return [];
    }
}

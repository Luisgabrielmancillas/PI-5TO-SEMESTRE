<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $title;
    public array $lines;

    public function __construct(string $title, array $lines)
    {
        $this->title = $title;
        $this->lines = $lines;
        $this->subject($title);
    }

    public function build()
    {
        return $this->subject($this->title)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->view('emails.user-status-changed')
            ->with(['lines' => $this->lines]);
    }
}
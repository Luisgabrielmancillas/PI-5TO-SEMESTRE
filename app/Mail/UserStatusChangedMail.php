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
        return $this->view('emails.user-status-changed')
                    ->with([
                        'title' => $this->title,
                        'lines' => $this->lines,
                    ]);
    }
}

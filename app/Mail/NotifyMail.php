<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyMail extends Mailable
{
    use Queueable, SerializesModels;
    private $boekname;

    public function __construct($boekname) {
        $this->boekname = $boekname;
    }

    public function build()
    {
        $boekname = $this->boekname;
        return $this->view('email')->with('boekname', $boekname);
    }
}

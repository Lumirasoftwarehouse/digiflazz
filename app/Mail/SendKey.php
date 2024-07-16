<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendKey extends Mailable
{
    use Queueable, SerializesModels;
    public $privateKey;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('key')->with(['privateKey' => $this->privateKey]);
    }
}

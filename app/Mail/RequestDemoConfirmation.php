<?php

namespace App\Mail;

use App\Models\RequestDemo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestDemoConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $demo;

    public function __construct(RequestDemo $demo)
    {
        $this->demo = $demo;
    }

    public function build()
    {
        return $this->subject('Your Demo Request is Scheduled')
            ->view('emails.request_demo_confirmation')
            ->with(['demo' => $this->demo]);
    }
} 
<?php

namespace App\Mail;

use App\Models\InternshipRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PengajuanBaruMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pengajuan;

    /**
     * Create a new message instance.
     */
    public function __construct(InternshipRequest $pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Pengajuan Magang / PKL Baru Diterima')
                    ->markdown('emails.pengajuan_baru');
    }
}

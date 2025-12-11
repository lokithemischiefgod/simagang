<?php

namespace App\Mail;

use App\Models\InternshipRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PengajuanStatusMail extends Mailable
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
        $subject = 'Status Pengajuan Magang / PKL Anda';

        if ($this->pengajuan->status === 'approved') {
            $subject = 'Pengajuan Magang / PKL Anda DITERIMA';
        } elseif ($this->pengajuan->status === 'rejected') {
            $subject = 'Pengajuan Magang / PKL Anda DITOLAK';
        }

        return $this->subject($subject)
                    ->markdown('emails.pengajuan_status');
    }
}

<?php

namespace App\Mail;

use App\Models\InternshipRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PengajuanStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public InternshipRequest $item;
    public ?string $plainPassword;

    public function __construct(InternshipRequest $item, ?string $plainPassword = null)
    {
        $this->item = $item;
        $this->plainPassword = $plainPassword;
    }

    public function build()
    {
        $subject = match ($this->item->status) {
            'approved' => 'Pengajuan Magang Disetujui - Akun SIMAGANG Anda',
            'rejected' => 'Pengajuan Magang Ditolak - SIMAGANG',
            default    => 'Update Status Pengajuan - SIMAGANG',
        };

        return $this
            ->subject($subject)
            ->markdown('emails.pengajuan_status', [
                'item' => $this->item,
                'plainPassword' => $this->plainPassword,
            ]);
    }

}

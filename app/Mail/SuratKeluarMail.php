<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuratKeluarMail extends Mailable
{
    use Queueable, SerializesModels;

    public $surat;

    public function __construct($surat)
    {
        $this->surat = $surat;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'))
            ->subject('Surat Keluar: ' . $this->surat->topik_surat)
            ->view('emails.surat_keluar')
            ->with([
                'nomor_surat' => $this->surat->nomor_surat,
                'topik_surat' => $this->surat->topik_surat,
                'isi_surat' => $this->surat->isi_surat,
            ]);
    }
}

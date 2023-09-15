<?php

namespace App\Models\Bpmn\PengajuanPerubahanJournalDanKas;

use Laravolt\Crud\UserTaskModel;

class RevisiPengajuan extends UserTaskModel
{
    protected $table = 'pengajuan_perubahan_journal_dan_kas_revisi_pengajuan';
    protected string $path = "/api/pengajuan-perubahan-journal-dan-kas/revisi-pengajuan";

}

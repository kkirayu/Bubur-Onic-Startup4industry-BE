<?php

namespace App\Models\Bpmn\PengajuanPerubahanJournalDanKas;

use Laravolt\Crud\UserTaskModel;

class ReviewDireksi extends UserTaskModel
{
    protected $table = 'pengajuan_perubahan_journal_dan_kas_review_direksi';
    protected string $path = "/api/pengajuan-perubahan-journal-dan-kas/review-direksi";
    protected array $processVariables = ["review_direksi"];


}

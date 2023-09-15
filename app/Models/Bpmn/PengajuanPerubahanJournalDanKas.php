<?php

namespace App\Models\Bpmn;

use Laravolt\Crud\BpmnModel;
use Laravolt\Crud\Input\Selection\UrlForeignSelection;

class PengajuanPerubahanJournalDanKas extends BpmnModel
{
    protected $table = 'pengajuan_perubahan_journal_dan_kas';
    protected $fillable = [

        "perusahaan_id" ,
        "cabang_id" ,
        "payload" ,
        "nama" , 
        "jenis_aksi"
    ];

    protected string $path = "/api/pengajuan-perubahan-journal-dan-kas";


    protected array $processVariables = ["jenis_aksi"];
    public function getPerusahaan_idSelection()
    {
        return new UrlForeignSelection("/api/saas/perusahaan", "GET", "id", "nama");
    }

    public function getCabang_idSelection()
    {
        return new UrlForeignSelection("/api/saas/perusahaan", "GET", "id", "nama");
    }
}

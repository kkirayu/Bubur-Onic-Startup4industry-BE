<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Enum\AutoMode;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BonusKaryawan extends CrudModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'bonus_karyawans';

    protected $fillable = [
        'bulan',
        'tahun',
        'id_profile_pegawai',
        'total_bonus',
        'alasan_bonus',
        'bukti_bonus',
        'status',
        'total_diambil',
    ];

    public AutoMode $filterMode = AutoMode::BLACKLIST;
    public AutoMode $searchMode = AutoMode::BLACKLIST;
    public AutoMode $sortMode = AutoMode::BLACKLIST;
}

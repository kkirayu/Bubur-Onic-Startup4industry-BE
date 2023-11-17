<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Spec\BaseTableValue;

class ProfilPegawai extends CrudModel
{

    use HideCompanyTrait;
    protected $table = 'profile_pegawais';

    protected string $path = "/api/pegawai/profil-pegawai";

    public function user () : BelongsTo{
        return $this->belongsTo(User::class, 'user_id');
    }

    public function team () : BelongsTo {
        return $this->belongsTo(Team::class, 'team_id');
    }


    public function tableValueMapping(): array
    {
        return [
            new BaseTableValue("user_id", "hasOne", "user", "name"),
            new BaseTableValue("team_id", "hasOne", "team", "nama"),
        ];
    }


}

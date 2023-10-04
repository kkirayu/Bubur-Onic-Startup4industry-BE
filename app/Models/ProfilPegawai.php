<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Laravolt\Crud\CrudModel;

class ProfilPegawai extends CrudModel
{

    use HideCompanyTrait;
    protected $table = 'profile_pegawais';

    protected string $path = "/api/pegawai/profil-pegawai";

}

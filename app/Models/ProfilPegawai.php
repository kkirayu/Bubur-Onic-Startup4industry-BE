<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;

class ProfilPegawai extends CrudModel
{
    protected $table = 'profile_pegawais';

    protected string $path = "/api/pegawai/profil-pegawai";

}

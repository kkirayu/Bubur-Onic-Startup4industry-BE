<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;

class ProfilPegawai extends CrudModel
{
    protected $table = 'profil_pegawais';

    protected string $path = "/api/pegawai/profil-pegawai";

}

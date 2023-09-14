<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;

class Perusahaan extends CrudModel
{
    protected $table = 'perusahaans';

    protected string $path = "/api/saas/perusahaan";


    public function cabang(){
        return $this->hasOne(Cabang::class, 'perusahaan_id', 'id');
    }



}

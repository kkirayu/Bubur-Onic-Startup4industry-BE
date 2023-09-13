<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;

class Perusahaan extends CrudModel
{
    protected $table = 'perusahaans';

    protected string $path = "/api/saas/perusahaan";

}

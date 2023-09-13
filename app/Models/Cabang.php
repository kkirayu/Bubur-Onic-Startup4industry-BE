<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;

class Cabang extends CrudModel
{
    protected $table = 'cabangs';

    protected string $path = "/api/saas/cabang";

}

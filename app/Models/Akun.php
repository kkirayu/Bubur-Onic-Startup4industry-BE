<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;

class Akun extends CrudModel
{
    protected $table = 'akuns';

    protected string $path = "/api/akun/akun";

}

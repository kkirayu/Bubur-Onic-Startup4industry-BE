<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;

class Kas extends CrudModel
{
    protected $table = 'kass';

    protected string $path = "/api/kas/kas";

}

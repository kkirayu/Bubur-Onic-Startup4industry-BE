<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;

class Journal extends CrudModel
{
    protected $table = 'journals';

    protected string $path = "/api/journal/journal";

    

}

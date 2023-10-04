<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Laravolt\Crud\CrudModel;

class Journal extends CrudModel
{

    use HideCompanyTrait;
    protected $table = 'journals';

    protected string $path = "/api/journal/journal";

    

}

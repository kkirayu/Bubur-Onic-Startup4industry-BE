<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Laravolt\Crud\CrudModel;

class Kas extends CrudModel
{

    use HideCompanyTrait;
    protected $table = 'kass';

    protected string $path = "/api/kas/kas";

}

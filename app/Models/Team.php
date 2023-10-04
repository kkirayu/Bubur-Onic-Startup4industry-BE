<?php

namespace App\Models;

use App\Traits\HideCompanyTrait;
use Laravolt\Crud\CrudModel;

class Team extends CrudModel
{


    use HideCompanyTrait;
        protected $table = 'teams';

    protected string $path = "/api/team/team";

}

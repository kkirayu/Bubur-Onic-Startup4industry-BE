<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;

class Team extends CrudModel
{
    protected $table = 'teams';

    protected string $path = "/api/team/team";

}

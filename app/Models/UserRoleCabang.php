<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;

class UserRoleCabang extends CrudModel
{
    protected $table = 'user_role_cabangs';

    protected string $path = "/api/saas/user-role-cabang";

}

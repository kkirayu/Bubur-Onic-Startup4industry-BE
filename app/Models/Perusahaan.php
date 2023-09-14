<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Laravolt\Crud\CrudModel;

class Perusahaan extends CrudModel
{
    protected $table = 'perusahaans';

    protected string $path = "/api/saas/perusahaan";


    public function cabang() : HasMany {
        return $this->hasMany(Cabang::class, 'perusahaan_id', 'id');
    }


    public function owner() : HasOneThrough {
        return $this->hasOneThrough(User::class, UserRoleCabang::class, 'perusahaan_id', 'id', 'id', 'user_id')
            ->join("acl_roles",  "acl_roles.id", "=", "user_role_cabangs.acl_roles_id")->where("acl_roles.name", "=", "OWNER_CABANG");
    }

}

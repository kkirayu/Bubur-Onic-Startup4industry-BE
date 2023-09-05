<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionRole extends CrudModel
{
    use HasUuids;
    protected $table = 'acl_permission_role';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';


    protected string $path = "/api/crud/acl_role_permissions";

    protected bool $canDetail = false;

    protected $casts = [
        'id' => 'string'
    ];

    protected $hidden = [
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];

    protected array $hiddenOnList = [
        'id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];

    protected array $hiddenOnCreate = [
        'id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];

    protected array $searchableColumns = [
        'id',
        'name',
    ];

    protected array $filterableColumns = [
        'id',
        'name',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Uuid::uuid4();
        });
    }

}

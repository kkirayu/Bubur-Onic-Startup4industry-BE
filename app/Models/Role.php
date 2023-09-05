<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\Spec\BaseSpec;
use Ramsey\Uuid\Uuid;

class Role extends CrudModel
{
    use HasUuids;
    use SoftDeletes;

    protected $table = 'acl_roles';

    protected string $path = '/api/crud/role';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected bool $canDetail = false;

    protected bool $canBulk = false;

    protected $casts = [
        'id' => 'string',
    ];

    protected $with = [
        'permissions',
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
        'modules.name',
    ];

    protected array $filterableColumns = [
        'id',
        'name',
        'modules.name',
    ];

    protected array $sortableColumns = [
        'id',
        'name',
        'modules.name',
    ];

    public function addAdditionalFieldSpec(): array
    {
        return [
            BaseSpec::createInstanceFromArray([
                'name' => 'permissions',
                'label' => 'Permissions',
            ]),
        ];
    }


    public function addPermissions($permissions = [])
    {
        $permission_key = array_keys($permissions);
        $permissionsInstance = Permission::whereIn('name', $permission_key)->get();
        foreach ($permissionsInstance as $permission) {
            PermissionRole::create([
                'role_id' => $this->id,
                'permission_id' => $permission->id,
                'access_level' => $permissions[$permission->name],
            ]);
        }
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'acl_permission_role', 'role_id', 'permission_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string)Uuid::uuid4();
        });
    }
}

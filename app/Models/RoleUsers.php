<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RoleUsers extends CrudModel
{
    use HasUuids;
    protected $table = 'role_users';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'role_id' => 'string'
    ];

    protected $fillable = [
        'role_id',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }
}

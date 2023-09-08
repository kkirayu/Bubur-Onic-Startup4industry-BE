<?php

namespace App\Models;

use App\Notifications\ResetPasswordApiNotification;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravolt\Crud\CrudModel;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Laravolt\Crud\Contracts\CrudUser;

class User extends CrudModel implements Authenticatable, CrudUser, CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable, AuthenticableTrait, CanResetPasswordTrait;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'users';
    protected string $path = '/api/crud/user';
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id');
    }


    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        if (request()->has('type')) {
            $this->notify(new ResetPasswordApiNotification($token));
        } else {
            $this->notify(new ResetPasswordNotification($token));
        }
    }
}

<?php

namespace App\Entities;

use App\Entities\Traits\Uuid;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use App\Entities\Traits\Scopes\UserScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Entities\Traits\Methods\UserMethod;
use App\Entities\Traits\SendUserPasswordReset;
use App\Entities\Traits\Attributes\UserAttribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Entities\Traits\Relationships\UserRelationship;

/**
 * Class User.
 */
class User extends Authenticatable
{
    use HasRoles,
        Notifiable,
        SendUserPasswordReset,
        SoftDeletes,
        UserAttribute,
        UserMethod,
        UserRelationship,
        UserScope,
        Uuid,
        HasApiTokens;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'avatar_type',
        'avatar_location',
        'password',
        'password_changed_at',
        'active',
        'confirmation_code',
        'confirmed',
        'timezone',
        'last_login_at',
        'last_login_ip',
        'enable',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @var array
     */
    protected $dates = ['last_login_at', 'deleted_at'];

    /**
     * The dynamic attributes from mutators that should be returned with the user object.
     * @var array
     */
    protected $appends = ['full_name'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'enable' => 'boolean',
        'confirmed' => 'boolean',
    ];
}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'e_password', 'api_token'
    ];

    protected $with = ['pegawai', 'roles'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'e_password',
    ];

    protected $guard_name = 'api';

    public function pegawai()
    {
        return $this->hasOne(MasterPegawai::class);
    }

    public function token()
    {
        return $this->hasOne(OauthAccessToken::class);
    }

    public function wilkers()
    {
        return $this->belongsToMany(Wilker::class, 'wilker_users');
    }
}

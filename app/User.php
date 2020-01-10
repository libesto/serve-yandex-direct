<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * Get the yandexDirectSetting record associated with the user.
     */
    public function yandexDirectSetting()
    {
        return $this->hasOne('App\YandexDirectSetting');
    }
    
    /**
     * Get the yandexDirectRun record associated with the user.
     */
    public function yandexDirectRun()
    {
        return $this->hasOne('App\YandexDirectRun');
    }
    
    /**
     * Get the yandexDirectResult records associated with the user.
     */
    public function yandexDirectResults()
    {
        return $this->hasMany('App\YandexDirectResult');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YandexDirectSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token', 'refresh_token', 'expires_in', 'login', 'campaigns', 'active_campaigns',
    ];
    
    /**
     * Get the user that has the yandexDirectSetting.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

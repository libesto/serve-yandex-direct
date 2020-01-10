<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YandexDirectRun extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'daily_run', 'next_run', 'running',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'next_run' => 'datetime',
    ];
    
    /**
     * Get the user that has the yandexDirectRun.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

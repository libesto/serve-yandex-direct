<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YandexDirectResult extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'run_result', 'start_time',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_time' => 'datetime',
    ];
    
    /**
     * Get the user that has the yandexDirectResult.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

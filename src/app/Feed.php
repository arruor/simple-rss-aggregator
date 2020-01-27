<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'url',
    ];

    /**
     * The attributes that are hidden.
     *
     * @var array
     */
    protected $hidden = ['user_id'];

    /**
     * Get the user that owns the feed.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

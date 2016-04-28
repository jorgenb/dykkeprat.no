<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    /**
     * @var string
     */
    protected $table = 'thread';

    /**
     * Override Eloquent default primary key.
     *
     * @var string
     */
    protected $primaryKey = 'threadid';

    /**
     * The attributes that should be hidden.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * Attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = [
        'dateline'
    ];

    /**
     * Get the forum that owns the thread.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function forum()
    {
        return $this->belongsTo('App\Forum', 'forumid');
    }

    /**
     * A thread has many posts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('App\Post', 'threadid', 'threadid');
    }
}

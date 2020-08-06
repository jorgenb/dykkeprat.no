<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    /**
     * @var string
     */
    protected $table = 'forum';

    /**
     * Override Eloquent default primary key.
     *
     * @var string
     */
    protected $primaryKey = 'forumid';

    /**
     * The attributes that should be hidden.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * Attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'dateline',
    ];

    /**
     * A forum has many threads.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads()
    {
        return $this->hasMany('App\Thread', 'forumid', 'forumid');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumUser extends Model
{
    /**
    * @var string
    */
    protected $table = 'user';

    /**
     * Override Eloquent default primary key.
     * 
     * @var string
     */
    protected $primaryKey = 'userid';

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
        'joindate'
    ];
}

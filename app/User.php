<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
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
     * The table associated with the model.
     */
    protected $table = 'user';

    /**
     * The primary key associated with the model.
     */
    protected $primaryKey = 'userid';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * A user has many posts.
     */
    public function posts()
    {
        return $this->hasMany('App\Post', 'userid');
    }

    /**
     * Build up the url to the gravatar.
     */
    public function gravatar($s = 80, $d = 'identicon', $r = 'g')
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($this->email)));
        $url .= "?s=$s&d=$d&r=$r";

        return $url;
    }
}

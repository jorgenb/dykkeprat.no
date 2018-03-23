<?php

namespace App;

use Decoda\Decoda;
use Decoda\Filter\UrlFilter;
use Decoda\Filter\CodeFilter;
use Decoda\Filter\ListFilter;
use Decoda\Hook\EmoticonHook;
use Decoda\Filter\ImageFilter;
use Decoda\Filter\QuoteFilter;
use Decoda\Filter\VideoFilter;
use Decoda\Hook\ClickableHook;
use Decoda\Filter\DefaultFilter;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * @var string
     */
    protected $table = 'post';

    /**
     * Override Eloquent default primary key.
     *
     * @var string
     */
    protected $primaryKey = 'postid';

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
     * Get the thread that owns the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function thread()
    {
        return $this->belongsTo('App\Forum', 'forumid');
    }

    /**
     * Get the user that owns the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'userid');
    }

    /**
     * Get the post's text and parse BB-code, mutating it to suit bootstrap.
     *
     * @param $value
     *
     * @return string
     */
    public function getPageTextAttribute($value)
    {
        $code = new Decoda();
        $code->addFilter(new DefaultFilter());
        $code->addFilter(new QuoteFilter());
        $code->addFilter(new UrlFilter());
        $code->addFilter(new CodeFilter());
        $code->addFilter(new VideoFilter());
        $code->addFilter(new ImageFilter());
        $code->addFilter(new ListFilter());
        $code->addHook(new ClickableHook());
        $code->addHook(new EmoticonHook());
        $code->reset($value);

        try {
            return $code->parse();
        } catch (\Exception $e) {
            return $value;
        }
    }
}

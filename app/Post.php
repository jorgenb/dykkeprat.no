<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use ScoutElastic\Searchable;

class Post extends Model
{
    use Searchable;

    protected $appends = [
        'parsed_page_text'
    ];

    protected $indexConfigurator = PostsIndexConfigurator::class;

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        return [
            'postid' => $array['postid'],
            'threadid' => $array['threadid'],
            'userid' => $array['userid'],
            'username' => $array['username'],
            'title' => $array['title'],
            'dateline' => $array['dateline'],
            'pagetext' => $array['pagetext'],
            'post_thanks_amount' => $array['post_thanks_amount'],
            'parsedpagetext' => $array['parsed_page_text']
        ];
    }

    protected $searchRules = [
        PostSearchRule::class
    ];

    // Here you can specify a mapping for model fields
    protected $mapping = [
        'properties' => [
            'title' => [
                'type' => 'text',
                // Also you can configure multi-fields, more details you can find here https://www.elastic.co/guide/en/elasticsearch/reference/current/multi-fields.html
                'fields' => [
                    'raw' => [
                        'type' => 'keyword',
                    ]
                ]
            ],
            'pagetext' => [
                'type' => 'text'
            ],
            'parsedpagetext' => [
                'type' => 'text'
            ]
        ]
    ];

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
        'ipaddress', 'infraction'
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
        return $this->belongsTo('App\Thread', 'threadid');
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
    public function getParsedPageTextAttribute()
    {
        $stripped_bb_pagetext = stripBbCode($this->pagetext);
        $stripped_html_pagetext = strip_tags($stripped_bb_pagetext);

        return $stripped_html_pagetext;
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
        return stripBbCode($value);
    }
}

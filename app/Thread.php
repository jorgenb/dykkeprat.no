<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use ScoutElastic\Searchable;

class Thread extends Model
{
    use Searchable;

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        return [
            'threadid' => $array['threadid'],
            'title' => $array['title'],
            'lastpost' => $array['lastpost'],
            'forumid' => $array['forumid'],
            'replycount' => $array['replycount'],
            'postusername' => $array['postusername'],
            'dateline' => $array['dateline'],
            'views' => $array['views'],
            'txaglist' => $array['taglist'],
            'keywords' => $array['keywords'],
            'postercount' => $array['postercount'],
        ];
    }

    protected $indexConfigurator = ThreadsIndexConfigurator::class;

    protected $searchRules = [
        ThreadSearchRule::class
    ];

    // Here you can specify a mapping for model fields
    protected $mapping = [
        'properties' => [
            'title' => [
                'type' => 'search_as_you_type',
                // Also you can configure multi-fields, more details you can find here https://www.elastic.co/guide/en/elasticsearch/reference/current/multi-fields.html
                'fields' => [
                    'raw' => [
                        'type' => 'keyword',
                    ]
                ]
            ]
        ]
    ];
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
    protected $hidden = [];

    /**
     * Attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'dateline',
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
     * A thread has many posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('App\Post', 'threadid', 'threadid');
    }
}

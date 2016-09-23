<?php

use Elasticsearch\ClientBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;


Route::get('privacy', function () {
    return view('privacy');
});


/*
|--------------------------------------------------------------------------
| REST
|--------------------------------------------------------------------------
*/
/**
 * Get a list of forums.
 */
Route::get('/', function () {

    $forums = \App\Forum::where('showprivate', 0)->where('threadcount', '>', 1)->orderby('replycount', 'desc')->get();

    return view('welcome', compact('forums'));
});

/**
 * Get current forum and list of threads.
 */
Route::get('/forum/{id}', function ($id) {

    $forum = \App\Forum::whereNotIn('forumid', [1,3,4,5,6,7,8])->findOrFail($id);
    $threads = \App\Thread::where('forumid', $id)->where('open', 1)->where('visible', 1)->orderBy('dateline', 'desc')->paginate(10);

    //return $threads;

    return view('thread', compact('forum', 'threads'));
});

/**
 * Get current thread and list of posts.
 */
Route::get('/forum/posts/{thread_id}', function ($id) {

    $thread = \App\Thread::whereNotIn('forumid', [1,3,4,5,6,7,8])->where('visible', 1)->where('open', 1)->findOrFail($id);
    $posts = \App\Post::where('threadid', $id)->where('visible', 1)->orderBy('dateline')->paginate(10);

    return view('post', compact('thread', 'posts'));
});

/**
 * Get user
 */
Route::get('/forum/user/{id}', function ($id) {

    $user = \App\ForumUser::findOrFail($id);

    return view('user', compact('user'));
});

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/
Route::get('/api/search', function ()  {


    $server = env('ELASTIC_SERVER');
    $user = env('ELASTIC_RO_USER');
    $secret = env('ELASTIC_RO_PASSWORD');

    $hosts = [
        "https://$user:$secret@$server:443"
    ];

    /**
     * Configure the Elasticsearch PHP Client
     * and logger.
     */
    $logger = new Logger('importlog');
    $logger->pushHandler(new StreamHandler(storage_path('logs/laravel.log'), Logger::WARNING));
    $client = ClientBuilder::create()
        ->setLogger($logger)
        ->setHosts($hosts)
        ->build();

    $query = \Illuminate\Support\Facades\Input::get('q');

    $params = [
        'index' => 'dykkeprat',
        'type' => 'user',
        'body' => [
            'query' => [
                'match' => [
                    'username' => $query
                ]
            ],
            'sort' => [
                [
                    'post_count' => [
                        'order' => 'desc'
                    ],
                ]
            ]
        ]
    ];


    $users = $client->search($params);

    $params = [
        'index' => 'dykkeprat',
        'type' => ['threads'],

        'body' => [
            // The problem with ordering results by views is
            // that Fredragstråden usually dominates.
            //'sort' => [
            //    'views' => [
            //        'order' => 'desc',
            //        'mode' => 'avg'
            //    ],
            //],
            'query' => [
                'bool' => [
                    'should' => [
                        'match_phrase_prefix' => ['title' => [
                            'query' => $query,
                            'operator' => 'and'
                        ]],
                        [
                            'nested' => [
                                'path' => 'posts',
                                'score_mode' => 'max',
                                'query' => [
                                    'bool' => [
                                        'should' => [
                                            'match_phrase_prefix' => ['posts.pagetext' => [
                                                'query' => $query,
                                                'operator' => 'and',
                                                'boost' => 3
                                            ]]
                                        ]
                                    ]
                                ],
                                'inner_hits' => [
                                    'size' => 1,
                                    'highlight' => [
                                        'pre_tags' => ['<mark>'],
                                        'post_tags' => ['</mark>'],
                                        'fields' => [
                                            'posts.pagetext' => [
                                                'fragment_size' => 300,
                                                'number_of_fragments' => 3,
                                                'no_match_size' => 300
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ];

    $threads = $client->search($params);

    $threads = new \Illuminate\Support\Collection($threads['hits']['hits']);
    $users = new \Illuminate\Support\Collection($users['hits']['hits']);

    $results = $users->merge($threads);

    return $results;
});



/*
|--------------------------------------------------------------------------
| SETUP
|--------------------------------------------------------------------------
| This is how we get relevant data from SQL to Elastic.
*/

//Route::get('/import/delete', function () {
//
//    /**
//     * Configure the Elasticsearch PHP Client
//     * and logger.
//     */
//
//    $server = env('ELASTIC_SERVER');
//    $user = env('ELASTIC_RW_USER');
//    $secret = env('ELASTIC_RW_PASSWORD');
//
//    $hosts = [
//        "https://$user:$secret@$server:443"
//    ];
//
//    $logger = new Logger('importlog');
//    $logger->pushHandler(new StreamHandler(storage_path('logs/laravel.log'), Logger::WARNING));
//    $client = ClientBuilder::create()
//        ->setLogger($logger)
//        ->setHosts($hosts)
//        ->build();
//
//
//    $params = ['index' => 'dykkeprat'];
//    $client->indices()->delete($params);
//
//    print 'done';
//});
//
//Route::get('/import/create', function () {
//
//
//    /**
//     * Configure the Elasticsearch PHP Client
//     * and logger.
//     */
//
//    $server = env('ELASTIC_SERVER');
//    $user = env('ELASTIC_RW_USER');
//    $secret = env('ELASTIC_RW_PASSWORD');
//
//    $hosts = [
//        "https://$user:$secret@$server:443"
//    ];
//
//    $logger = new Logger('importlog');
//    $logger->pushHandler(new StreamHandler(storage_path('logs/laravel.log'), Logger::WARNING));
//    $client = ClientBuilder::create()
//        ->setLogger($logger)
//        ->setHosts($hosts)
//        ->build();
//
//    $params = [
//        'index' => 'dykkeprat',
//    ];
//
//    $client->indices()->create($params);
//
//
//    $params = [
//        'index' => 'dykkeprat',
//        'type' => 'threads',
//        'body' => [
//            //'mappings' => [
//            'threads' => [
//                'properties' => [
//                    'posts' => [
//                        'type' => 'nested'
//                        //'properties' => [
//                        //    'postid' => ['type' => 'short'],
//                        //    'threadid' => ['type' => 'short'],
//                        //    'parentid' => ['type' => 'short'],
//                        //    'username' => ['type' => 'string'],
//                        //    'userid' => ['type' => 'short'],
//                        //    'title' => ['type' => 'string'],
//                        //    'dateline' => ['type' => 'date'],
//                        //    'lastedit' => ['type' => 'short'],
//                        //    'pagetext' => ['type' => 'string'],
//                        //    'allowsmilie' => ['type' => 'short'],
//                        //    'ipaddress' => ['type' => 'string'],
//                        //    'iconid' => ['type' => 'short'],
//                        //    'visible' => ['type' => 'short'],
//                        //    'attach' => ['type' => 'short'],
//                        //    'infraction' => ['type' => 'short'],
//                        //    'reportthreadid' => ['type' => 'short'],
//                        //    'ame_flag' => ['type' => 'short'],
//                        //    'post_thanks_amount' => ['type' => 'short'],
//                        //    'html_state' => ['type' => 'string']
//                        //]
//                    ]
//                ]
//            ]
//        ]
//        //]
//    ];
//    $client->indices()->putMapping($params);
//
//    print 'done';
//});
//
//
//
///**
// * Import data to Elasticsearch
// * Simple hack to publish data that should be searchable to Elastic.
// */
//
//Route::get('/import/forum', function () {
//
//
//    $forums = \App\Forum::where('showprivate', 0)->where('threadcount', '>', 1)->orderby('replycount', 'desc')->get();
//
//    /**
//     * Configure the Elasticsearch PHP Client
//     * and logger.
//     */
//
//    $server = env('ELASTIC_SERVER');
//    $user = env('ELASTIC_RW_USER');
//    $secret = env('ELASTIC_RW_PASSWORD');
//
//    $hosts = [
//        "https://$user:$secret@$server:443",
//    ];
//
//    $logger = new Logger('importlog');
//    $logger->pushHandler(new StreamHandler(storage_path('logs/laravel.log'), Logger::WARNING));
//    $client = ClientBuilder::create()
//        ->setLogger($logger)
//        ->setHosts($hosts)
//        ->build();
//
//
//    /**
//     * Start the actual import
//     */
//    $params = ['body' => []];
//
//    foreach ($forums as $key => $forum) {
//
//        $params['body'][] = [
//            'index' => [
//                '_index' => 'dykkeprat',
//                '_type' => 'forum',
//                '_id' => $forum->forumid
//            ]
//        ];
//
//        $params['body'][] = [
//            'title' => $forum->title,
//            'description' => $forum->description,
//            'showprivate' => $forum->showprivate,
//            'replycount' => $forum->replycount
//        ];
//
//        // Every 1000 documents stop and send the bulk request
//        if ($key % 1000 == 0) {
//            $responses = $client->bulk($params);
//
//            // erase the old bulk request
//            $params = ['body' => []];
//
//            // unset the bulk response when you are done to save memory
//            unset($responses);
//        }
//    }
//
//    // Send the last batch if it exists
//    if (!empty($params['body'])) {
//        $responses = $client->bulk($params);
//    }
//
//    print 'done';
//});
//
//Route::get('/import/user', function () {
//
//
//    $users = \App\ForumUser::where('usergroupid', 2)->orWhere('usergroupid', 6)->orderBy('userid')->get();
//
//    /**
//     * Configure the Elasticsearch PHP Client
//     * and logger.
//     */
//
//    $server = env('ELASTIC_SERVER');
//    $user = env('ELASTIC_RW_USER');
//    $secret = env('ELASTIC_RW_PASSWORD');
//
//    $hosts = [
//        "https://$user:$secret@$server:443"
//    ];
//
//    $logger = new Logger('importlog');
//    $logger->pushHandler(new StreamHandler(storage_path('logs/laravel.log'), Logger::WARNING));
//    $client = ClientBuilder::create()
//        ->setLogger($logger)
//        ->setHosts($hosts)
//        ->build();
//
//    /**
//     * Start the actual import
//     */
//    $params = ['body' => []];
//
//    foreach ($users as $key => $user) {
//
//        $params['body'][] = [
//            'index' => [
//                '_index' => 'dykkeprat',
//                '_type' => 'user',
//                '_id' => $user->userid
//            ]
//        ];
//
//        $params['body'][] = [
//            'userid' => $user->userid,
//            'usergroupid' => $user->usergroupid,
//            'username' => $user->username,
//            'email' => $user->email,
//            'homepage' => $user->homepage,
//            'usertitle' => $user->usertitle,
//            'joindate' => $user->joindate,
//            'post_count' => $user->posts,
//            'profilevisits' => $user->profilevisits,
//            'post_thanks_user_amount' => $user->post_thanks_user_amount,
//            'post_thanks_thanked_posts' => $user->post_thanks_thanked_post,
//            'post_thanks_thanked_times' => $user->post_thanks_thanked_times
//        ];
//
//        // Every 1000 documents stop and send the bulk request
//        if ($key % 1000 == 0) {
//            $responses = $client->bulk($params);
//
//            // erase the old bulk request
//            $params = ['body' => []];
//
//            // unset the bulk response when you are done to save memory
//            unset($responses);
//        }
//    }
//
//    // Send the last batch if it exists
//    if (!empty($params['body'])) {
//        $responses = $client->bulk($params);
//    }
//
//    print 'done';
//});
//
//Route::get('/import/thread_post', function () {
//
//    ini_set('max_execution_time', 600);
//    ini_set('memory_limit', '1024M');
//
//    $threads = \App\Thread::with('posts')->where('open', 1)->whereNotIn('forumid', [3,4,5,6,8])->get();
//
//
//    /**
//     * Configure the Elasticsearch PHP Client
//     * and logger.
//     */
//
//    $server = env('ELASTIC_SERVER');
//    $user = env('ELASTIC_RW_USER');
//    $secret = env('ELASTIC_RW_PASSWORD');
//
//    $hosts = [
//        "https://$user:$secret@$server:443"
//    ];
//
//    $logger = new Logger('importlog');
//    $logger->pushHandler(new StreamHandler(storage_path('logs/laravel.log'), Logger::INFO));
//    $client = ClientBuilder::create()
//        ->setLogger($logger)
//        ->setHosts($hosts)
//        ->build();
//
//
//    // DELETE
//
//    //$params = ['index' => 'dykkeprat'];
//    //$client->indices()->delete($params);
//
//    // CREATE
//
//    //$params = [
//    //    'index' => 'dykkeprat',
//    //];
//
//    //$client->indices()->create($params);
//
//
//    // MAPPINGS
//
//    $params = [
//        'index' => 'dykkeprat',
//        'type' => 'threads',
//        'body' => [
//            //'mappings' => [
//            'threads' => [
//                'properties' => [
//                    'posts' => [
//                        'type' => 'nested',
//                        //'properties' => [
//                        //    'postid' => ['type' => 'short'],
//                        //    'threadid' => ['type' => 'short'],
//                        //    'parentid' => ['type' => 'short'],
//                        //    'username' => ['type' => 'string'],
//                        //    'userid' => ['type' => 'short'],
//                        //    'title' => ['type' => 'string'],
//                        //    'dateline' => ['type' => 'date'],
//                        //    'lastedit' => ['type' => 'short'],
//                        //    'pagetext' => ['type' => 'string'],
//                        //    'allowsmilie' => ['type' => 'short'],
//                        //    'ipaddress' => ['type' => 'string'],
//                        //    'iconid' => ['type' => 'short'],
//                        //    'visible' => ['type' => 'short'],
//                        //    'attach' => ['type' => 'short'],
//                        //    'infraction' => ['type' => 'short'],
//                        //    'reportthreadid' => ['type' => 'short'],
//                        //    'ame_flag' => ['type' => 'short'],
//                        //    'post_thanks_amount' => ['type' => 'short'],
//                        //    'html_state' => ['type' => 'string']
//                        //]
//                    ]
//                ]
//            ]
//        ]
//        //]
//    ];
//    $client->indices()->putMapping($params);
//
//    /**
//     * Start the actual import
//     */
//    $params = ['body' => []];
//
//    foreach ($threads as $key => $thread) {
//
//        $posts = new \Illuminate\Support\Collection();
//        foreach ($thread->posts as $post) {
//            $posts->push([
//                'pagetext' => $post->pagetext
//            ]);
//        }
//
//
//        $params['body'][] = [
//            'index' => [
//                '_index' => 'dykkeprat',
//                '_type' => 'threads',
//                '_id' => $thread->threadid,
//
//            ]
//
//        ];
//
//        $params['body'][] = [
//            'title' => $thread->title,
//            'forumid' => $thread->forumid,
//            'open' => $thread->open,
//            'replycount' => $thread->replycount,
//            'postuserid' => $thread->postuserid,
//            'postusername' => $thread->postusername,
//            'dateline' => $thread->dateline,
//            'views' => $thread->views,
//            'visible' => $thread->visible,
//            'sticky' => $thread->sticky,
//            'posts' => $posts
//
//        ];
//
//        // Every 1000 documents stop and send the bulk request
//        if ($key % 1000 == 0) {
//            $responses = $client->bulk($params);
//
//            // erase the old bulk request
//            $params = ['body' => []];
//
//            // unset the bulk response when you are done to save memory
//            unset($responses);
//        }
//    }
//
//    // Send the last batch if it exists
//    if (!empty($params['body'])) {
//        $responses = $client->bulk($params);
//    }
//
//    print 'done';
//});

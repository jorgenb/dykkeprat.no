<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dykkeprat.no</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="/css/all.css" media="screen">

    <style>
        [v-cloak] {
            display: none;
        }

        #forum tr td:nth-child(1){
            width:80%;
        }

        .wrapper-search {
            background: #b81f24;
            padding-top: 35px;
            padding-bottom: 5px;
            margin-bottom: 35px;
        }


        .search-text {
            color: whitesmoke;
        }

        .dykkeprat {
            width: 88px;
        }

        .logo {
            width: 200px;
            position: relative;
        }


        mark {
            background-color: #b81f24;
            color: whitesmoke;
        }

        .pagetext {
            font-size: 65%;
        }

        .decoda-quote-body {
            font-size: 65%;
        }

        .navbar-brand>img {

            display: inline-block !important;

        }

        .navbar {
            background: #66605b;
        }

        .navbar-nav>li>a {
            color: whitesmoke !important;
        }

        .search-text>a {
            color: whitesmoke !important;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}"><img class="logo" src="/images/dykkeprat.png"></a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li class="navbar-link"><a href="{{ url('https://www.facebook.com/groups/dykkeprat/') }}">Dykkeprat på Facebook</a></li>
            </ul>
        </div>
    </div>
</nav>

<section class="wrapper wrapper-search">
    <div class="container">
        <div class="row">
            <div class="col-md-1 hidden-xs hidden-sm hidden-md">
                <a href="{{ url('/') }}"><img src="/images/dykkeprat_logo.png" class="dykkeprat"></a>
            </div>
            <div class="col-md-7">
                    <div class="input-group">
                        <input type="text" @keyup="search | debounce 100" v-model="query" class="form-control" placeholder="Søk etter '{!! $search_tag_placeholder  !!}'">
                        <span class="input-group-btn">

                          <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
                        </span>
                    </div><!-- /input-group -->

                <h6 class="search-text">Forumet ble <a href="{{ '/forum/posts/5485' }}">stengt</a> i oktober 2015. Her kan du søke i alle tråder og innlegg fra 2009 til 2015.</h6>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <ul class="list-group" id="results-template" v-cloak>
        <li v-for="result in results" v-if="result._source.username" class="list-group-item">
            <h5>
                <span class="glyphicon glyphicon-user"></span>
                <a href="{{url('forum/user')}}/@{{result._id}}">@{{result._source.username}}</a>
            </h5>
            <small>
                <p>Tittel: @{{ result._source.usertitle }}</p>
                <p>Medlem siden: @{{ result._source.joindate.date }}</p>
                <p>Antall innlegg: @{{ result._source.post_count }}</p>
            </small>
        </li>
        <li v-for="result in results" v-if="result._source.title" class="list-group-item">
            <div class="row">
                <div class="col-md-12">
                    <h5>
                        <a href="{{url('forum/posts')}}/@{{result._id}}">
                            <span class="glyphicon glyphicon-th-list"></span>
                            @{{result._source.title}}
                        </a>
                    </h5>

                    <small><span class="glyphicon glyphicon-user"></span> @{{result._source.postusername}}</small>
                    <small><span class="glyphicon glyphicon-eye-open"></span>  @{{ result._source.views }}</small>
                    <small><span class="glyphicon glyphicon-pencil"></span>  @{{ result._source.dateline.date }}</small>
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <blockquote>
                        <p class="pagetext" v-for="post in result.inner_hits.posts.hits.hits">@{{{ post.highlight['posts.pagetext'] }}}</p>
                    </blockquote>
                </div>
            </div>
        </li>
    </ul>

    @yield('content')
</div>
<footer class="footer">
    <div class="container">
        <hr>
        <small>
            <p class="text-muted">Tjenesten er laget av <a href="{{ url('https://www.facebook.com/jorgen.birkhaug') }}">Jørgen Birkhaug</a></p>
            <p class="text-muted">Logo laget av <a href="{{ url('https://www.facebook.com/hans.kaland') }}">Hans Fredrik Kaland</a>.</p>
            <p class="text-muted">Koden for denne lille appen er tilgjengelig her: <a href="{{ url('https://github.com/jorgenb/dykkeprat.no') }}">https://github.com/jorgenb/dykkeprat.no</a></p>
            <p class="text-muted">Dette nettstedet bruker '<a href="{{ url('https://no.wikipedia.org/wiki/Informasjonskapsel') }}">cookies</a>' og du må slutte å bruke dette nettstedet dersom du ikke aksepterer det.</p>
        </small>
    </div>
</footer>

<script src="/js/all.js"></script>
<script type="text/javascript">/* <![CDATA[ */(function(d,s,a,i,j,r,l,m,t){try{l=d.getElementsByTagName('a');t=d.createElement('textarea');for(i=0;l.length-i;i++){try{a=l[i].href;s=a.indexOf('/cdn-cgi/l/email-protection');m=a.length;if(a&&s>-1&&m>28){j=28+s;s='';if(j<m){r='0x'+a.substr(j,2)|0;for(j+=2;j<m&&a.charAt(j)!='X';j+=2)s+='%'+('0'+('0x'+a.substr(j,2)^r).toString(16)).slice(-2);j++;s=decodeURIComponent(s)+a.substr(j,m-j)}t.innerHTML=s.replace(/</g,'&lt;').replace(/>/g,'&gt;');l[i].href='mailto:'+t.value}}catch(e){}}}catch(e){}})(document);/* ]]> */</script>


</body>
</html>

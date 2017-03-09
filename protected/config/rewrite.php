<?php

return array(
    'urlFormat' => 'path', //get
    'showScriptName' => false, //隐藏index.php   
    'urlSuffix' => '', //后缀   
    'rules' => array(
        'category/<cid:\d+>' => 'showcase/index',
        'book/<id:\d+>' => 'book/view',
        'book/chapter-<cid:\d+>' => 'book/chapter',
        'books/list-<colid:\d+>' => 'book/index',
        'books' => 'book/index',
        'author/<id:\d+>' => 'author/view',
        'user/<id:\d+>' => 'user/index',
        'forums' => 'posts/types',
        'forum-<forum:\d+>' => 'posts/index',
        'forum/<type:\w+>' => 'posts/index',        
        'addPost/<forum:\d+>' => 'posts/create',
        'updatePost/<id:\d+>' => 'posts/create',
        'replyPost/<tid:\d+>' => 'posts/reply',
        'post/<id:\d+>' => 'posts/view',
        'activity/<id:\d+>' => 'activity/view',
        'siteInfo/<code:\w+>' => 'site/info',
        //文库
        'wenku/p<id:\d+>' => 'wenku/post',
        'wenku/a<id:\d+>' => 'wenku/author',
        'wenku/b<id:\d+>' => 'wenku/book',
        'wenku' => 'wenku/index',
        //站点地图
        'sitemap/all' => array(
            'sitemap/all',
            'urlSuffix' => '.xml'
        ),
        'sitemap/<type:\w+>-<page:\d+>' => array(
            'sitemap/list',
            'urlSuffix' => '.xml'
        ),
        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',        
    )
);
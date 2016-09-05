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
        'author/<id:\d+>' => 'author/view',
        'user/<id:\d+>' => 'user/index',
        'forum-<forum:\d+>' => 'posts/index',
        'forum/<type:\w+>' => 'posts/index',
        'updatePost/<id:\d+>' => 'posts/create',
        'post/<id:\d+>' => 'posts/view',
        'siteInfo/<code:\w+>' => 'site/info',
        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',        
    )
);
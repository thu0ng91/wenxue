<?php

return array(
    'urlFormat' => 'path', //get
    'showScriptName' => false, //隐藏index.php   
    'urlSuffix' => '', //后缀   
    'rules' => array(
        'map' => 'index/map',
        'tags' => 'index/tags',
        'magazines' => 'zazhi/index',
        'magazine/<zid:\d+>/chapter/<id:\d+>' => 'zazhi/chapter',
        'magazine/<zid:\d+>' => 'zazhi/view',
        'post/<id:\d+>' => 'posts/view',
        'tag/<tagid:\d+>' => 'index/index',
        'siteInfo/<code:\w+>' => 'site/info',
        'posts' => 'index/index',
        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',        
    )
);
?>

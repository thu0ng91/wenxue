<?php

return array(
    'urlFormat' => 'path', //get
    'showScriptName' => false, //隐藏index.php   
    'urlSuffix' => '', //后缀   
    'rules' => array(
        'cate/<id:\d+>' => 'posts/view',
        'post/<id:\d+>' => 'posts/view',
        'siteInfo/<code:\w+>' => 'site/info',
        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',        
    )
);
?>

<?php

$rewrite = require('rewrite.php');

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Momo',
    'theme' => 'web',
    'preload' => array('log'),
    'onBeginRequest' => create_function('$event', 'return ob_start("ob_gzhandler");'),
    'onEndRequest' => create_function('$event', 'return ob_end_flush();'),
    'import' => array(
        'application.models.*',
        'application.controllers.*',
        'application.components.*',
        'application.helpers.*'
    ),
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => '123456',
            'ipFilters' => array(
                '127.0.0.1',
                '192.168.1.141',
                '::1'
            )
        ),
        'admin' => array(
            'class' => 'application.modules.admin.AdminModule',
            'defaultController' => 'index'
        ),
    ),
    'defaultController' => 'index',
    'components' => array(
        'request' => array(
            'enableCsrfValidation' => false, //防范跨站请求伪造(简称CSRF)攻击              
            'enableCookieValidation' => true, //对cookie的值进行HMAC检查
        ),
        'user' => array(
            'identityCookie' => array('domain' => '.newsoul.cn', 'path' => '/'),
            'allowAutoLogin' => true,
            'stateKeyPrefix' => 'zmf_newsoul',
            'loginUrl' => array(
                'site/login'
            )
        ),
        'session' => array(
        //'cookieParams' => array('domain' => '.newsoul.cn', 'lifetime' => 0), //配置会话ID作用域 生命期和超时  
        //'timeout' => 3600, 
        ),
        'statePersister' => array(
            'class' => 'system.base.CStatePersister',
            'stateFile' => dirname(__FILE__) . '/../runtime/state.bin',
        ),
        'db' => require(dirname(__FILE__) . '/../runtime/config/db.php'),
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        //'urlManager' => $rewrite,
        'filecache' => array(
            'class' => 'system.caching.CFileCache',
            'directoryLevel' => '2', //缓存文件的目录深度  
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
    ),
    'params' => array(
        'c' => require(dirname(__FILE__) . '/../runtime/config/zmfconfig.php'),
        'author' => '@阿年飞少',
        'copyrightInfo' => 'COPYRIGHT&copy;2015-2016 blog.newsoul.cn BY 阿年飞少.',
    )
);

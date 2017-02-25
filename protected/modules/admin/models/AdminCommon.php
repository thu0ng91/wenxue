<?php

class AdminCommon extends CActiveRecord {

    public static function navbar() {
        $c = Yii::app()->getController()->id;
        $a = Yii::app()->getController()->getAction()->id;
        $attr['login'] = array(
            'title' => '<i class="fa fa-tachometer"></i> 首页',
            'url' => Yii::app()->createUrl('admin/index/index'),
            'active' => in_array($c, array('index'))
        );
        $attr['content'] = array(
            'title' => '<i class="fa fa-align-justify"></i> 内容',
            'url' => '#',
            'active' => in_array($c, array('books','postThreads','comments')),
            'seconds' => array(
                'books' => array(
                    'title' => '小说',
                    'url' => Yii::app()->createUrl('admin/books/index'),
                    'active' => in_array($c, array('books'))
                ),
                'postThreads' => array(
                    'title' => '帖子',
                    'url' => Yii::app()->createUrl('admin/postThreads/index'),
                    'active' => in_array($c, array('postThreads'))
                ),
                'digest' => array(
                    'title' => '首页图文',
                    'url' => Yii::app()->createUrl('admin/digest/index'),
                    'active' => in_array($c, array('digest'))
                ),
                'comments' => array(
                    'title' => '评论',
                    'url' => Yii::app()->createUrl('admin/comments/index'),
                    'active' => in_array($c, array('comments'))
                ),
            )
        );
        $attr['yunying'] = array(
            'title' => '<i class="fa fa-line-chart"></i> 运营',
            'url' => '#',
            'active' => in_array($c, array('task','groupTasks','showcases','column','postForums','tags','words','feedback','reports')),
            'seconds' => array(
                'task' => array(
                    'title' => '任务',
                    'url' => Yii::app()->createUrl('admin/task/index'),
                    'active' => in_array($c, array('task'))
                ),
                'groupTasks' => array(
                    'title' => '用户组任务',
                    'url' => Yii::app()->createUrl('admin/groupTasks/index'),
                    'active' => in_array($c, array('groupTasks'))
                ),  
                'activity' => array(
                    'title' => '活动',
                    'url' => Yii::app()->createUrl('admin/activity/index'),
                    'active' => in_array($c, array('activity'))
                ),  
                'showcases' => array(
                    'title' => '首页版块',
                    'url' => Yii::app()->createUrl('admin/showcases/index'),
                    'active' => in_array($c, array('showcases'))
                ),
                'column' => array(
                    'title' => '作品分类',
                    'url' => Yii::app()->createUrl('admin/column/index'),
                    'active' => in_array($c, array('column'))
                ),
                'postForums' => array(
                    'title' => '帖子版块',
                    'url' => Yii::app()->createUrl('admin/postForums/index'),
                    'active' => in_array($c, array('postForums'))
                ),
                'tags' => array(
                    'title' => '帖子标签',
                    'url' => Yii::app()->createUrl('admin/tags/index'),
                    'active' => in_array($c, array('tags'))
                ),
                'words' => array(
                    'title' => '敏感词',
                    'url' => Yii::app()->createUrl('admin/words/index'),
                    'active' => in_array($c, array('words'))
                ),
                'keywords' => array(
                    'title' => '关键词',
                    'url' => Yii::app()->createUrl('admin/keywords/index'),
                    'active' => in_array($c, array('keywords'))
                ),
                'msg' => array(
                    'title' => '短信',
                    'url' => Yii::app()->createUrl('admin/msg/index'),
                    'active' => in_array($c, array('msg'))
                ),
                'feedback' => array(
                    'title' => '反馈',
                    'url' => Yii::app()->createUrl('admin/feedback/index'),
                    'active' => in_array($c, array('feedback'))
                ),
                'reports' => array(
                    'title' => '举报',
                    'url' => Yii::app()->createUrl('admin/reports/index'),
                    'active' => in_array($c, array('reports'))
                )
            )
        );
        $attr['shop'] = array(
            'title' => '<i class="fa fa-shopping-cart"></i> 商城',
            'url' => '#',
            'active' => in_array($c, array('goods','goodsClassify')),
            'seconds' => array(
                'goods' => array(
                    'title' => '商品',
                    'url' => Yii::app()->createUrl('admin/goods/index'),
                    'active' => in_array($c, array('goods'))
                ),
                'goodsClassify' => array(
                    'title' => '商品分类',
                    'url' => Yii::app()->createUrl('admin/goodsClassify/index'),
                    'active' => in_array($c, array('goodsClassify'))
                ),
            )
        );
        $attr['users'] = array(
            'title' => '<i class="fa fa-users"></i> 用户',
            'url' => '#',
            'active' => in_array($c, array('authors','users','group','groupPowerTypes','groupPowers')),
            'seconds' => array(
                'authors' => array(
                    'title' => '作者',
                    'url' => Yii::app()->createUrl('admin/authors/index'),
                    'active' => in_array($c, array('authors'))
                ),
                'user' => array(
                    'title' => '用户',
                    'url' => Yii::app()->createUrl('admin/users/index'),
                    'active' => in_array($c, array('users'))
                ),                
                'group' => array(
                    'title' => '用户组',
                    'url' => Yii::app()->createUrl('admin/group/index'),
                    'active' => in_array($c, array('group'))
                ),
                'groupLevels' => array(
                    'title' => '用户等级',
                    'url' => Yii::app()->createUrl('admin/groupLevels/index'),
                    'active' => in_array($c, array('groupLevels'))
                ),
                'groupPowerTypes' => array(
                    'title' => '权限分类',
                    'url' => Yii::app()->createUrl('admin/groupPowerTypes/index'),
                    'active' => in_array($c, array('groupPowerTypes'))
                ),
                'groupPowers' => array(
                    'title' => '用户组权限',
                    'url' => Yii::app()->createUrl('admin/groupPowers/index'),
                    'active' => in_array($c, array('groupPowers'))
                ),
                'admins' => array(
                    'title' => '管理员',
                    'url' => Yii::app()->createUrl('admin/users/admins'),
                    'active' => in_array($a, array('admins'))
                ),
            )
        );
        $attr['wenku'] = array(
            'title' => '<i class="fa fa-book"></i> 文库',
            'url' => '#',
            'seconds' => array(
                'wenkuColumns' => array(
                    'title' => '朝代',
                    'url' => Yii::app()->createUrl('admin/wenkuColumns/index'),
                    'active' => in_array($c, array('wenkuColumns'))
                ),
                'wenkuAuthor' => array(
                    'title' => '作者',
                    'url' => Yii::app()->createUrl('admin/wenkuAuthor/index'),
                    'active' => in_array($c, array('wenkuAuthor'))
                ),
                'wenkuBook' => array(
                    'title' => '文学合集',
                    'url' => Yii::app()->createUrl('admin/wenkuBook/index'),
                    'active' => in_array($c, array('wenkuBook'))
                ),
                'wenkuPosts' => array(
                    'title' => '诗词歌赋',
                    'url' => Yii::app()->createUrl('admin/wenkuPosts/index'),
                    'active' => in_array($c, array('wenkuPosts'))
                ),
            )
        );
        $attr['system'] = array(
            'title' => '<i class="fa fa-cog"></i> 系统',
            'url' => Yii::app()->createUrl('admin/config/index'),
            'active' => in_array($c, array('site', 'config')),
            'seconds' => array(
                'configBaseinfo' => array(
                    'title' => '网站信息',
                    'url' => Yii::app()->createUrl('admin/config/index',array('type'=>'baseinfo')),
                    'active' => in_array($_GET['type'], array('baseinfo'))
                ),
                'configBase' => array(
                    'title' => '全局配置',
                    'url' => Yii::app()->createUrl('admin/config/index',array('type'=>'base')),
                    'active' => in_array($_GET['type'], array('base'))
                ),
                'configEmail' => array(
                    'title' => '邮件配置',
                    'url' => Yii::app()->createUrl('admin/config/index',array('type'=>'email')),
                    'active' => in_array($_GET['type'], array('email'))
                ),
                'configUpload' => array(
                    'title' => '上传配置',
                    'url' => Yii::app()->createUrl('admin/config/index',array('type'=>'upload')),
                    'active' => in_array($_GET['type'], array('upload'))
                ),
                'siteFiles' => array(
                    'title' => '站点文件',
                    'url' => Yii::app()->createUrl('admin/siteFiles/index'),
                    'active' => in_array($c, array('siteFiles'))
                ),
            )
        );
        foreach ($attr as $k => $v) {
            if (!Controller::checkPower($k, '', true)) {
                //unset($attr[$k]);
                continue;
            } elseif (!empty($v['seconds'])) {
                foreach ($v['seconds'] as $_spower => $_second) {
                    if (!Controller::checkPower($_spower, '', true)) {
                        //unset($attr[$k]);
                    }
                }
            }
        }
        return $attr;
    }

}

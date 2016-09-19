<?php

class AdminCommon extends CActiveRecord {

    public static function navbar() {
        $c = Yii::app()->getController()->id;
        $a = Yii::app()->getController()->getAction()->id;
        $attr['login'] = array(
            'title' => '首页',
            'url' => Yii::app()->createUrl('admin/index/index'),
            'active' => in_array($c, array('index'))
        );
        $attr['content'] = array(
            'title' => '内容',
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
                'comments' => array(
                    'title' => '评论',
                    'url' => Yii::app()->createUrl('admin/comments/index'),
                    'active' => in_array($c, array('comments'))
                ),
            )
        );
        $attr['yunying'] = array(
            'title' => '运营',
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
            'title' => '商城',
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
            'title' => '用户',
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
                'groupPowerTypes' => array(
                    'title' => '用户组权限分类',
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
        $attr['system'] = array(
            'title' => '系统',
            'url' => Yii::app()->createUrl('admin/config/index'),
            'active' => in_array($c, array('site', 'config'))
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

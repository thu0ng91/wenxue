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
        $attr['showcases'] = array(
            'title' => '版块',
            'url' => Yii::app()->createUrl('admin/showcases/index'),
            'active' => in_array($c, array('showcases'))
        );
        $attr['authors'] = array(
            'title' => '作者',
            'url' => Yii::app()->createUrl('admin/authors/index'),
            'active' => in_array($c, array('authors'))
        );
        $attr['books'] = array(
            'title' => '小说',
            'url' => Yii::app()->createUrl('admin/books/index'),
            'active' => in_array($c, array('books'))
        );
        $attr['comments'] = array(
            'title' => '评论',
            'url' => Yii::app()->createUrl('admin/comments/index'),
            'active' => in_array($c, array('comments'))
        );
        $attr['feedback'] = array(
            'title' => '反馈',
            'url' => Yii::app()->createUrl('admin/feedback/index'),
            'active' => in_array($c, array('feedback'))
        );
        $attr['zazhi'] = array(
            'title' => '杂志',
            'url' => Yii::app()->createUrl('admin/zazhi/index'),
            'active' => in_array($c, array('zazhi'))
        );        
        $attr['posts'] = array(
            'title' => '文章',
            'url' => Yii::app()->createUrl('admin/posts/index'),
            'active' => in_array($c, array('posts'))
        );        
        $attr['group'] = array(
            'title' => '标签',
            'url' => Yii::app()->createUrl('admin/tags/index'),
            'active' => in_array($c, array('tags'))
        );
        
        $attr['user'] = array(
            'title' => '用户',
            'url' => Yii::app()->createUrl('admin/users/index'),
            'active' => in_array($c, array('users'))
        );
        $attr['attachments'] = array(
            'title' => '图片',
            'url' => Yii::app()->createUrl('admin/attachments/index'),
            'active' => in_array($c, array('attachments'))
        );
        $attr['siteInfo'] = array(
            'title' => '站点',
            'url' => Yii::app()->createUrl('admin/siteInfo/index'),
            'active' => in_array($c, array('siteInfo'))
        );
        $attr['system'] = array(
            'title' => '系统',
            'url' => Yii::app()->createUrl('admin/config/index'),
            'active' => in_array($c, array('site','config'))
        );
        foreach ($attr as $k => $v) {
            if (!Controller::checkPower($k, '', true)) {
                //unset($attr[$k]);
            }
        }
        return $attr;
    }

}

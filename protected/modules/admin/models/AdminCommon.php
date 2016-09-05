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
            'title' => '内容管理',
            'url' => Yii::app()->createUrl('admin/index/index'),
            'active' => in_array($c, array('index'))
        );
        $attr['users'] = array(
            'title' => '用户管理',
            'url' => Yii::app()->createUrl('admin/index/index'),
            'active' => in_array($c, array('index'))
        );
        $attr['showcases'] = array(
            'title' => '版块',
            'url' => Yii::app()->createUrl('admin/showcases/index'),
            'active' => in_array($c, array('showcases'))
        );
        $attr['column'] = array(
            'title' => '分类',
            'url' => Yii::app()->createUrl('admin/column/index'),
            'active' => in_array($c, array('column'))
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
        $attr['postThreads'] = array(
            'title' => '帖子',
            'url' => Yii::app()->createUrl('admin/postThreads/index'),
            'active' => in_array($c, array('postThreads'))
        );
        $attr['postForums'] = array(
            'title' => '帖子版块',
            'url' => Yii::app()->createUrl('admin/postForums/index'),
            'active' => in_array($c, array('postForums'))
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
        $attr['reports'] = array(
            'title' => '举报',
            'url' => Yii::app()->createUrl('admin/reports/index'),
            'active' => in_array($c, array('reports'))
        );
        $attr['tags'] = array(
            'title' => '标签',
            'url' => Yii::app()->createUrl('admin/tags/index'),
            'active' => in_array($c, array('tags'))
        );        
        $attr['user'] = array(
            'title' => '用户',
            'url' => Yii::app()->createUrl('admin/users/index'),
            'active' => in_array($c, array('users'))
        );
        $attr['words'] = array(
            'title' => '敏感词',
            'url' => Yii::app()->createUrl('admin/words/index'),
            'active' => in_array($c, array('words'))
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

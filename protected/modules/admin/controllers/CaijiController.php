<?php

/**
 * @filename CaijiController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2017-3-6  16:31:10 
 */
class CaijiController extends Admin {

    public function actionAuthor() {
        $dir = "H:\\wamp\\www\\poets\\shiren";
        $files = zmf::readDir($dir, false);
        $num = 100;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $start = ($page - 1) * $num;
        $data = array_slice($files, $start, $num);
        if (!empty($data)) {
            foreach ($data as $name) {
                $_dir = $dir . '\\' . $name;
                $_arr = include($_dir);
                $_attr = array(
                    'title_en' => $_arr['title']
                );
                $model = new WenkuAuthor;
                $model->attributes = $_attr;
                if ($model->save()) {
                    $_infoAttr = array(
                        'author' => $model->id,
                        'classify' => WenkuAuthorInfo::CLASSIFY_INFO,
                        'content' => $_arr['content'],
                        'title' => '英文介绍',
                    );
                    $_model = new WenkuAuthorInfo();
                    $_model->attributes = $_infoAttr;
                    $_model->save();
                }
            }
            $success = '正在处理第' . $page . '页';
            $url = Yii::app()->createUrl('admin/caiji/author', array('page' => ($page + 1)));
            $this->message(1, $success, $url, 1);
        } else {
            exit('WELL DONE!!');
        }
    }

    public function actionPosts() {
        $dir = "H:\\wamp\\www\\poets\\shi";
        $files = zmf::readDir($dir, false);
        $num = 100;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $start = ($page - 1) * $num;
        $data = array_slice($files, $start, $num);
        if (!empty($data)) {
            foreach ($data as $name) {
                $_dir = $dir . '\\' . $name;
                $_arr = include($_dir);
                $_arr['author'] = trim($_arr['author']);
                $_ainfo = WenkuAuthor::findByName($_arr['author']);
                $_attr = array(
                    'title_en' => $_arr['title'],
                    'author' => $_ainfo['id'],
                    'content' => trim($_arr['content'])
                );
                $model = new WenkuPosts;
                $model->attributes = $_attr;
                $model->save();
            }
            $success = '正在处理第' . $page . '页';
            $url = Yii::app()->createUrl('admin/caiji/posts', array('page' => ($page + 1)));
            $this->message(1, $success, $url, 1);
        } else {
            exit('WELL DONE!!');
        }
    }

}

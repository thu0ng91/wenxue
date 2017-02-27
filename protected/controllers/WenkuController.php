<?php

/**
 * @filename WenkuController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2017-2-21  14:08:53 
 */
class WenkuController extends Q {

    public function init() {
        parent::init();
        $this->selectNav = 'wenku';
    }

    public function actionIndex() {
        $criteria = new CDbCriteria();
        $criteria->order = 't.hits DESC';
        $char = zmf::val('char', 1);
        //所属作者
        $author = zmf::val('author', 2);
        if ($author) {
            $criteria->addCondition("t.author='{$author}'");
        }
        //所属朝代
        $dynasty = zmf::val('dynasty', 2);
        if ($dynasty) {
            $criteria->addCondition("t.dynasty='{$dynasty}'");
        }
        //类型
        $type = zmf::val('type', 2);
        if ($type) {
            $criteria->addCondition("t.colid='{$type}'");
        }
        $criteria->select = 't.id,t.author,t.title,t.content,wa.title AS authorName';
        $criteria->join = 'LEFT JOIN {{wenku_author}} wa ON t.author=wa.id';
        $criteria->addCondition('t.status=1');
        $model = new WenkuPosts;
        $count = $model->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = $model->findAll($criteria);
        $this->pageTitle = '诗词文库 - ' . zmf::config('sitename');
        //热门作者
        $topAuthors = WenkuAuthor::getTops(10);
        //热门作品
        $topPosts = WenkuPosts::getTops(10);
        //朝代
        $dyArr = WenkuColumns::getAll();
        $typeArr = WenkuColumns::getAll(WenkuColumns::CLASSIFY_TYPES);
        $this->render('index', array(
            'pages' => $pager,
            'posts' => $posts,
            'topAuthors' => $topAuthors,
            'topPosts' => $topPosts,
            'dyArr' => $dyArr,
            'typeArr' => $typeArr
        ));
    }

    public function actionAuthor() {
        $id = zmf::val('id', 2);
        if (!$id) {
            throw new CHttpException(404, '您所查看的页面不存在');
        }
        $info = WenkuAuthor::getOne($id);
        if (!$info) {
            throw new CHttpException(404, '您所查看的页面不存在');
        } elseif ($info['status'] != Posts::STATUS_PASSED) {
            if (!$this->userInfo['isAdmin']) {
                throw new CHttpException(404, '您所查看的页面不存在或已删除');
            }
        }
        if(!$info['faceImg']){
            $info['faceImg']='https://img2.chuxincw.com/siteinfo/2017/02/18/32DD3F5E-18B2-DA78-549C-9E7F89731A1B.png';
        }
        $aboutInfos = WenkuAuthorInfo::model()->findAll(array(
            'condition'=>'author=:author AND status='.Posts::STATUS_PASSED,
            'params'=>array(
                ':author'=>$id
            ),
            'order'=>'classify ASC'
        ));
        foreach ($aboutInfos as $k => $aboutInfo) {
            $aboutInfos[$k]['content'] = zmf::text(array(), $aboutInfo['content'], true);
        }
        //相关作品
        $posts = WenkuPosts::model()->findAll(array(
            'condition' => 'author=:author AND status=' . Posts::STATUS_PASSED,
            'select' => 'id,title',
            'order' => 'hits DESC',
            'limit' => 10,
            'params' => array(
                ':author' => $id
            )
        ));
        $this->pageTitle=$info['title'].'简介、故事、作品 - 诗词文库 - '.zmf::config('sitename');
        $data = array(
            'info' => $info,
            'aboutInfos' => $aboutInfos,
            'posts' => $posts,
        );
        $this->render('author', $data);
    }

    public function actionBook() {
        $id = zmf::val('id', 2);
        if (!$id) {
            throw new CHttpException(404, '您所查看的页面不存在');
        }

        $this->render('book', $data);
    }

    public function actionPost() {
        $id = zmf::val('id', 2);
        if (!$id) {
            throw new CHttpException(404, '您所查看的页面不存在');
        }
        $info = WenkuPosts::getOne($id);
        if (!$info) {
            throw new CHttpException(404, '您所查看的页面不存在');
        } elseif ($info['status'] != Posts::STATUS_PASSED) {
            if (!$this->userInfo['isAdmin']) {
                throw new CHttpException(404, '您所查看的页面不存在或已删除');
            }
        }
        $aboutInfos=$info->aboutInfos; 
        foreach ($aboutInfos as $k => $aboutInfo) {
            $aboutInfos[$k]['content'] = zmf::text(array(), $aboutInfo['content'], true);
        }
        $authorInfo = array();
        if ($info['author'] > 0) {
            $authorInfo = WenkuAuthor::getOne($info['author']);
        }
        $this->pageTitle=$info['title'].'注释、翻译及原创改编 - 诗词文库 - '.zmf::config('sitename');
        $data = array(
            'info' => $info,
            'aboutInfos' => $aboutInfos,
            'authorInfo' => $authorInfo
        );
        $this->render('post', $data);
    }

}

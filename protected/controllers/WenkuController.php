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

    public function actionIndex() {
        $criteria = new CDbCriteria();
        $criteria->order = 't.hits DESC';
        $char = zmf::val('char',1);
        //所属作者
        $author = zmf::val('author', 2);
        if ($author) {
            $criteria->addCondition("t.author='{$author}'");
        }
        //所属朝代或类型
        $colid = zmf::val('colid', 2);
        if ($colid) {
            $criteria->addCondition("t.dynasty='{$colid}'");
        }
        $criteria->select = 't.id,t.author,t.title,t.content,wa.title AS authorName';
        $criteria->join='LEFT JOIN {{wenku_author}} wa ON t.author=wa.id';
        $model=new WenkuPosts;
        $count = $model->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = $model->findAll($criteria);
        $this->pageTitle = '古文列表 - ' . zmf::config('sitename');
        //热门作者
        $topAuthors = WenkuAuthor::getTops(10);
        //热门作品
        $topPosts = WenkuPosts::getTops(10);
        
        $this->render('index', array(
            'pages' => $pager,
            'posts' => $posts,
            'topAuthors' => $topAuthors,
            'topPosts' => $topPosts,
        ));
    }

    public function actionAuthor() {
        $this->render('author',$data);
    }

    public function actionBook() {
        $id=  zmf::val('id',2);
        if(!$id){
            throw new CHttpException(404, '您所查看的页面不存在');
        }
        
        $this->render('book',$data);
    }

    public function actionPost() {
        $id=  zmf::val('id',2);
        if(!$id){
            throw new CHttpException(404, '您所查看的页面不存在');
        }
        $info=  WenkuPosts::getOne($id);
        if(!$info){
            throw new CHttpException(404, '您所查看的页面不存在');
        }elseif($info['status']!=Posts::STATUS_PASSED){
            if(!$this->userInfo['isAdmin']){
                throw new CHttpException(404, '您所查看的页面不存在或已删除');
            }
        }
        $authorInfo=array();
        
        $data=array(
            'info'=>$info
        );
        $this->render('post',$data);
    }

}

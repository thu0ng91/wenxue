<?php

class PostsController extends Q {
    
    public $favorited = false;

    public function actionView() {
        $id = zmf::val('id', 2);
        if (!$id) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $info = $this->loadModel($id);
        if($info['zazhi']>0){
            $this->redirect(array('zazhi/chapter','id'=>$id,'zid'=>$info['zazhi']));
        }
        $pageSize = 30;
        $comments = Comments::getCommentsByPage($id, 'posts', 1, $pageSize);       
        $tags = Tags::getByIds($info['tagids']);
        $relatePosts=  Posts::getRelations($id, 5);
        if (!zmf::actionLimit('visit-Posts', $id, 5, 60)) {
            Posts::updateCount($id, 'Posts', 1, 'hits');
        }
        $size='600';
        if($this->isMobile){
            $size='640';
        }
        $info['content']=zmf::text(array(),$info['content'],true,$size);
        $data = array(
            'info' => $info,
            'comments' => $comments,
            'tags' => $tags,
            'relatePosts' => $relatePosts,
            'loadMore' => count($comments) == $pageSize ? 1 : 0,
        );
        $this->favorited=  Favorites::checkFavored($id, 'post');
        $this->pageTitle=$info['title'];
        $this->selectNav='posts';
        $this->render('view', $data);
    }
    
    public function actionCreate($id = '') {    
        $this->onlyOnPc();
        $id = zmf::myint($id);
        if (!$this->uid) {
            $this->redirect(array('site/login'));
        }        
        if ($id) {
            $model = $this->loadModel($id);
            $isNew = false;
        } else {
            $model = new Posts;
            $isNew = true;            
        }
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'posts-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        if (isset($_POST['Posts'])) {
            //处理文本
            $filter = Posts::handleContent($_POST['Posts']['content']);            
            $_POST['Posts']['content'] = $filter['content'];
            if (!empty($filter['attachids'])) {
                $attkeys = array_filter(array_unique($filter['attachids']));
                if (!empty($attkeys)) {
                    $_POST['Posts']['faceimg'] = $attkeys[0]; //默认将文章中的第一张图作为封面图
                }
            } else {
                $_POST['Posts']['faceimg'] = ''; //否则将封面图置为空(有可能编辑后没有图片了)
            }            
            $tagids = array_unique(array_filter($_POST['tags']));
            $_POST['Posts']['status']=  Posts::STATUS_STAYCHECK;
            $model->attributes = $_POST['Posts'];
            if ($model->save()) {
                //将上传的图片置为通过
                Attachments::model()->updateAll(array('status' => Posts::STATUS_DELED), 'logid=:logid AND classify=:classify', array(':logid' => $model->id, ':classify' => 'posts'));
                if (!empty($attkeys)) {
                    $attstr = join(',', $attkeys);
                    if ($attstr != '') {
                        Attachments::model()->updateAll(array('status' => Posts::STATUS_PASSED, 'logid' => $model->id), 'id IN(' . $attstr . ')');
                    }
                }
                $this->redirect(array('users/posts'));
            }
        }
        $this->selectNav='contribution';
        $this->pageTitle = '投稿 - ' . zmf::config('sitename');
        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function loadModel($id) {
        $model = Posts::model()->findByPk($id);
        if ($model === null || $model->status!=Posts::STATUS_PASSED)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}

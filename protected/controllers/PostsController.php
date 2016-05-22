<?php

class PostsController extends Q {

    public $favorited = false;

    public function actionIndex() {
        $type = zmf::val('type', 1);
        if (!in_array($type, array('author', 'reader'))) {
            $type = 'author';
        }
        $classify = 0;
        if ($type == 'author') {
            $classify = Posts::CLASSIFY_AUTHOR;
            $label = '作者专区';
            $sql = "SELECT p.id,p.title,p.aid,a.authorName AS username,p.cTime,p.comments,p.favors,p.classify FROM {{posts}} p,{{authors}} a WHERE p.classify='{$classify}' AND p.status=" . Posts::STATUS_PASSED." AND p.aid=a.id AND a.status=".Posts::STATUS_PASSED." ORDER BY p.cTime DESC";
        } elseif ($type == 'reader') {
            $classify = Posts::CLASSIFY_READER;
            $label = '读者专区';
            $sql = "SELECT p.id,p.title,p.uid,u.truename AS username,p.cTime,p.comments,p.favors,p.classify FROM {{posts}} p,{{users}} u WHERE p.classify='{$classify}' AND p.status=" . Posts::STATUS_PASSED." AND p.uid=u.id AND u.status=".Posts::STATUS_PASSED." ORDER BY p.cTime DESC";
        }
        Posts::getAll(array('sql' => $sql), $pages, $posts);
        $this->selectNav = $type . 'Forum';
        $data = array(
            'posts' => $posts,
            'label' => $label,
            'type' => $type,
        );
        $this->render('index', $data);
    }

    public function actionView() {
        $id = zmf::val('id', 2);
        if (!$id) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $info = $this->loadModel($id);
        $pageSize = 30;
        $comments = Comments::getCommentsByPage($id, 'posts', 1, $pageSize);
        if (!zmf::actionLimit('visit-Posts', $id, 5, 60)) {
            Posts::updateCount($id, 'Posts', 1, 'hits');
        }
        $size = '600';
        if ($this->isMobile) {
            $size = '640';
        }
        $info['content'] = zmf::text(array(), $info['content'], true, $size);
        $data = array(
            'info' => $info,
            'comments' => $comments,
            'loadMore' => count($comments) == $pageSize ? 1 : 0,
        );
        $this->favorited = Favorites::checkFavored($id, 'post');
        $this->selectNav = 'authorForum';
        $this->pageTitle = $info['title'];
        $this->render('view', $data);
    }

    public function actionCreate($id = '') {
        $this->onlyOnPc();
        if (!$this->uid) {
            $this->redirect(array('site/login'));
        }
        if ($id) {
            $id = zmf::myint($id);
            $model = $this->loadModel($id);
            if($model['uid']!=$this->uid){
                throw new CHttpException(403, '你无权本操作');
            }
            $isNew = false;
            $type=  Posts::exType($model->classify);
            if($model->classify==Posts::CLASSIFY_AUTHOR && $this->userInfo['authorId'] && !$model->aid){
                $model->aid=$this->userInfo['authorId'];
            }
        } else {
            $type = zmf::val('type', 1);
            if (!in_array($type, array('author', 'reader'))) {
                throw new CHttpException(404, '你所选择的版块不存在');
            }            
            $model = new Posts;
            if($type=='author' && !$this->userInfo['authorId']){
                throw new CHttpException(404, '你不能在该版块发帖');
            }elseif($type=='author' && $this->userInfo['authorId']){
                $model->aid=$this->userInfo['authorId'];
            }
            $model->classify=  Posts::exType($type);
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
                
                //处理标签
                $intoTags = array();
                if (!empty($tagids)) {
                    foreach ($tagids as $tagid) {
                        $_info = Tags::addRelation($tagid, $model->id, $type);
                        if ($_info) {
                            $intoTags[] = $tagid;
                        }
                    }
                }
                if (!$isNew || !empty($intoTags)) {
                    Posts::model()->updateByPk($model->id, array('tagids' => join(',', $intoTags)));
                }
                $this->redirect(array('posts/view','id'=>$model->id));
            }
        }
        
        $tags=  Tags::getClassifyTags($type);
        $this->selectNav = 'contribution';
        $this->pageTitle = '投稿 - ' . zmf::config('sitename');
        $this->render('create', array(
            'model' => $model,
            'tags' => $tags,
        ));
    }

    public function loadModel($id) {
        $model = Posts::model()->findByPk($id);
        if ($model === null || $model->status != Posts::STATUS_PASSED)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}

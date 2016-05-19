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
        } elseif ($type == 'reader') {
            $classify = Posts::CLASSIFY_READER;
            $label = '读者专区';
        }
        $sql = "SELECT id,title,uid,cTime,comments,favors FROM {{posts}} WHERE classify='{$classify}' AND status=" . Posts::STATUS_PASSED." ORDER BY cTime DESC";
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
            $isNew = false;
        } else {
            $type = zmf::val('type', 1);
            if (!in_array($type, array('author', 'reader'))) {
                throw new CHttpException(404, '你所选择的版块不存在');
            }            
            $model = new Posts;
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
                $this->redirect(array('posts/view','id'=>$model->id));
            }
        }
        $this->selectNav = 'contribution';
        $this->pageTitle = '投稿 - ' . zmf::config('sitename');
        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function loadModel($id) {
        $model = Posts::model()->findByPk($id);
        if ($model === null || $model->status != Posts::STATUS_PASSED)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}

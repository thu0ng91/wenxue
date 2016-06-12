<?php

class PostsController extends Admin {

    public function init() {
        parent::init();
        $this->checkPower('posts');
    }

    public function actionIndex() {
        $uid = zmf::val('uid', 2);
        $username = zmf::val('username', 1);
        $start = zmf::val('start', 1);
        $end = zmf::val('end', 1);
        $orderBy = zmf::val('orderBy', 1);
        $select = "id,uid,aid,title,classify,status";
        $criteria = new CDbCriteria();    
        $model = new Posts();
        $type = zmf::val('type', 1);    
        if($type=='stayCheck'){
            $criteria->addCondition('status='.Posts::STATUS_STAYCHECK);
        }else{
            $criteria->addCondition('status!='.Posts::STATUS_DELED);
        }
        if ($username) {
            $uinfo = Users::model()->find("username LIKE '%{$username}%'");
            if ($uinfo) {
                $uid = $uinfo['id'];
            }
        }
        if ($uid) {
            $criteria->addCondition("uid='{$uid}'");
        }
        if ($start) {
            $start = strtotime($start, $now);
            $criteria->addCondition("cTime>='{$start}'");
        }
        if ($end) {
            $end = strtotime($end, $now);
            $criteria->addCondition("cTime<='{$end}'");
        }
        if ($orderBy == 'hits') {
            $criteria->order = 'hits DESC';
        } elseif ($orderBy == 'favors') {
            $criteria->order = 'favors DESC';
        } elseif ($orderBy == 'imgs') {
            $criteria->order = 'imgs DESC';
        } elseif ($orderBy == 'videos') {
            $criteria->order = 'videos DESC';
        } else {
            $criteria->order = 'cTime DESC';
        }
        $criteria->select = $select;
        $count = $model->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = $model->findAll($criteria);

        $this->render('/posts/content', array(
            'model' => $model,
            'pages' => $pager,
            'posts' => $posts,
            'from' => 'posts',
            'selectArr' => explode(',', $select),
        ));
    }

    public function actionCreate($id = '') {
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
            $model->attributes = $_POST['Posts'];
            if ($model->save()) {
                if($model->status==Posts::STATUS_NOTPASSED){
                    $this->redirect(array('posts/index'));
                }else{
                    $this->redirect(array('/posts/view', 'id' => $model->id));
                }
            }
        }
        $this->pageTitle = '与世界分享你的旅行见闻 - ' . zmf::config('sitename');
        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id) {
        $this->actionCreate($id);
    }
    
    public function actionStayCheck($id){
        $info=  $this->loadModel($id);
        $info['title']=  Words::highLight($info['title']);
        $info['content']=  Words::highLight($info['content']);
        $data=array(
            'info'=>$info
        );
        $this->render('stayCheck',$data);
    }

    public function loadModel($id) {
        $model = Posts::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}

<?php

class UserController extends Q {

    public $toUserInfo;
    public $authorLogin=false;
    public $favorited=false;

    public function init() {
        parent::init();        
        $this->layout = 'user';
        $id = zmf::val('id', 2);
        if(!$id && !$this->uid){
            throw new CHttpException(404, '您所查看的用户不存在，请核实');
        }elseif (!$id || $id==$this->uid) {
            $this->toUserInfo = $this->userInfo;
        }else{
            $this->toUserInfo =  Users::getOne($id);
        }
        if($this->toUserInfo['id']==$this->uid){
            $this->authorLogin=  Authors::checkLogin($this->userInfo, $this->userInfo['authorId']);
        }else{
            Posts::updateCount($id, 'Users', 1, 'hits');
        }
    }
    
    private function checkLogin(){
        if (!$this->uid) {
            $this->redirect(array('site/login'));
        }
    }
    
    private function showError(){}
    
    public function actionIndex(){
        $this->selectNav = 'index';
        $this->render('index', array(
            'model' => $model,
        ));
    }
    
    public function actionFollow(){
        $type=  zmf::val('type',1);
        if($type=='fans'){
            $sql="SELECT u.id,u.truename,u.avatar FROM {{users}} u,{{favorites}} f WHERE f.logid='{$this->toUserInfo['id']}' AND f.classify='user' AND f.uid=u.id ORDER BY f.cTime DESC";
            $label='关注者';
            $render='fans';
        }elseif ($type=='authors') {
            $sql="SELECT a.id,a.authorName,a.avatar FROM {{authors}} a,{{favorites}} f WHERE f.uid='{$this->toUserInfo['id']}' AND f.classify='author' AND f.logid=a.id ORDER BY f.cTime DESC";
            $label='关注作者';
            $render='authors';
        }else{
            $sql="SELECT u.id,u.truename,u.avatar FROM {{users}} u,{{favorites}} f WHERE f.uid='{$this->toUserInfo['id']}' AND f.classify='user' AND f.logid=u.id ORDER BY f.cTime DESC";
            $label='关注了';
            $render='fans';
        }
        Posts::getAll(array('sql'=>$sql), $pages, $posts);
        $this->selectNav='index';
        $data = array(
            'label' => $label,
            'posts' => $posts,
        );
        $this->render($render, $data);
    }

    public function actionAuthor() {
        $this->checkLogin();
        $authorInfo=  Authors::findByUid($this->uid);
        if($authorInfo){
            throw new CHttpException(403, '您已成为作者，请勿重复操作');
        }
        $this->selectNav = 'setting';
        $model = new Authors;
        $model->uid = $this->uid;
        if (isset($_POST['Authors'])) {
            if ($_POST['Authors']['password']) {
                $_POST['Authors']['hashCode'] = zmf::randMykeys(6);
                $_POST['Authors']['password'] = md5($_POST['Authors']['password'] . $_POST['Authors']['hashCode']);
            }
            $model->attributes = $_POST['Authors'];
            if ($model->save()) {
                Users::model()->updateByPk($this->uid, array('authorId'=>$model->id));
                $this->redirect(array('author/view','id'=>$model->id));
            }
        }
        $this->render('createAuthor', array(
            'model' => $model,
        ));
    }
    
    public function actionAuthorAuth(){
        $this->checkLogin();
        $authorInfo=  Authors::findByUid($this->uid);
        if(!$authorInfo){
            throw new CHttpException(403, '您尚未成为作者');
        }
        $model = new Authors;
        if (isset($_POST['Authors'])) {
            $password=$_POST['Authors']['password'];
            if (!$password) {
                $model->addError('password', '请填写密码');
            }elseif(md5($password . $authorInfo['hashCode'])!=$authorInfo['password']){
                $model->addError('password', '密码错误，请重试');
            }else{
                $code='authorAuth-'.$this->uid;
                Yii::app()->session[$code]=true;
                $this->redirect(array('author/view','id'=>$authorInfo['id']));
            }
        }
        $this->render('authorAuth', array(
            'model' => $model,
        ));
    }
    
    public function actionComment(){
        $this->selectNav = 'comment';
        $this->render('comment', array(
            'model' => $model,
        ));
    }
    
    public function actionFavorite(){
        $this->selectNav = 'favorite';
        $this->render('favorite', array(
            'model' => $model,
        ));
    }
    
    public function actionSetting(){
        $this->selectNav = 'setting';
        $this->render('setting', array(
            'model' => $model,
        ));
    }

    public function actionNotice() {
        $this->checkLogin();
        $this->selectNav = 'notice';
        $sql = "SELECT * FROM {{notification}} WHERE uid='{$this->uid}' ORDER BY cTime DESC";
        Posts::getAll(array('sql' => $sql), $pages, $comLists);
        Notification::model()->updateAll(array('new' => 0), 'uid=:uid', array(':uid' => $this->uid));
        $data = array(
            'posts' => $comLists,
            'pages' => $pages,
        );
        $this->pageTitle = $this->userInfo['truename'] . '的提醒 - ' . zmf::config('sitename');
        $this->render('notice', $data);
    }

    public function actionPosts() {
        $sql = "SELECT id,title,status FROM {{posts}} WHERE uid='{$this->uid}' ORDER BY cTime DESC";
        Posts::getAll(array('sql' => $sql), $pages, $posts);
        $data = array(
            'posts' => $posts,
            'pages' => $pages,
        );
        $this->pageTitle = $this->userInfo['truename'] . '的文章 - ' . zmf::config('sitename');
        $this->render('posts', $data);
    }

}

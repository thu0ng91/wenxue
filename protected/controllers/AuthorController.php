<?php

class AuthorController extends Q {

    public $authorInfo = array();
    public $adminLogin = false;
    public $favorited=false;

    public function init() {
        parent::init();
        $this->layout = 'author';
        $id = zmf::val('id', 2);
        if(!$id && !$this->userInfo['authorId']){
            throw new CHttpException(404, '您所查看的作者不存在，请核实');
        }elseif (!$id) {
            $id=  $this->userInfo['authorId'];
        }
        $this->authorInfo = Authors::getOne($id);
        if ($this->uid) {
            $this->adminLogin=  Authors::checkLogin($this->userInfo,$id);
            $this->favorited=  Favorites::checkFavored($id, 'author');
        }
    }

    public function actionView() {
        $posts = Books::model()->findAll(array(
            'condition' => 'aid=:aid'.(!$this->adminLogin ? " AND bookStatus='".Books::STATUS_PUBLISHED."'" : ""),
            'select' => 'id,colid,title,faceImg,`desc`,words,cTime,score,scorer,bookStatus',
            'params' => array(
                ':aid' => $this->authorInfo['id']
            )
        ));        
        $this->selectNav='index';
        Posts::updateCount($id, 'Authors', 1, 'hits');
        $data = array(
            'posts' => $posts
        );
        $this->render('view', $data);
    }
    
    public function actionFans(){
        $sql="SELECT u.id,u.truename,u.avatar FROM {{users}} u,{{favorites}} f WHERE f.logid='{$this->authorInfo['id']}' AND f.classify='author' AND f.uid=u.id ORDER BY f.cTime DESC";
        Posts::getAll(array('sql'=>$sql), $pages, $posts);
        $this->selectNav='fans';
        $data = array(
            'posts' => $posts
        );
        $this->render('fans', $data);
    }
    
    public function actionCreateBook($bid = ''){
        if ($bid) {
            $model = Books::getOne($bid);
            if(!$model || $model['status']!=Posts::STATUS_PASSED){
                throw new CHttpException(404, '您所编辑的小说不存在，请核实');
            }elseif($model['uid']!=$this->uid || $model['aid']!=$this->userInfo['authorId']){
                throw new CHttpException(403, '您无权改操作');
            }
        } else {
            $model = new Books;
            $model->uid=  $this->uid;
            $model->aid=  $this->userInfo['authorId'];
        }
        if (isset($_POST['Books'])) {
            $model->attributes = $_POST['Books'];
            if ($model->save()) {
                $this->redirect(array('index'));                
            }
        }
        $this->selectNav='createBook';
        $this->render('createBook', array(
            'model' => $model,
        ));
    }
    public function actionUpdateBook($bid) {
        $bid=  zmf::val('bid',2);
        $this->actionCreateBook($bid);
    }
    
    public function actionBook($bid){
        $bid=  zmf::val('bid',2);
        $info=  Books::getOne($bid);
        if(!$info){
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $chapters=  Chapters::getByBook($bid,$this->adminLogin);
        
        $data=array(
            'info'=>$info,
            'chapters'=>$chapters,
        );
        $this->render('book', $data);
    }
    
    public function actionAddChapter($cid=''){
        $this->layout = 'common';
        if ($cid) {
            $model = Chapters::getOne($cid);
            if(!$model){
                throw new CHttpException(404, '你所编辑的内容不存在');
            }elseif ($model['uid']!=$this->uid) {
                throw new CHttpException(403, '你无权本操作');
            }
        } else {
            $bid=  zmf::val('bid',2);
            if(!$bid){
                throw new CHttpException(404, '请选择小说');
            }
            $model = new Chapters;
            $bookInfo=  Books::getOne($bid);            
            if($bookInfo){
                $model->bid=$bid;
                $model->uid=$bookInfo['uid'];
                $model->aid=$bookInfo['aid'];
            }
        }
        if (isset($_POST['Chapters'])) {
            $title=  zmf::filterInput($_POST['Chapters']['title'],1);
            $content= Chapters::handleContent($_POST['Chapters']['content']);
            $postscript=  zmf::filterInput($_POST['Chapters']['postscript'],1);
            $psPosition=  zmf::filterInput($_POST['Chapters']['psPosition'],2);
            $attr=array(
                'title'=>$title,
                'content'=>$content,
                'postscript'=>$postscript,
                'psPosition'=>($psPosition<0 || $psPosition>1) ? 0 : $psPosition,
            ); 
            $model->attributes = $attr;
            if ($model->save()) {
                $this->redirect(array('author/book','bid'=>$model->bid));                
            }
        }
        $this->pageTitle='写文章';
        $this->render('addChapter', array(
            'model' => $model,
        ));
    }
    
    public function actionLogout(){
        if(!$this->userInfo['authorId']){
            throw new CHttpException(403, '操作有误');
        }elseif(!$this->adminLogin){
            throw new CHttpException(403, '您已退出，请勿重复操作');
        }
        $code = 'authorAuth-' . $this->uid;
        unset(Yii::app()->session[$code]);
        $this->redirect(array('user/index','id'=>  $this->uid));
    }

}

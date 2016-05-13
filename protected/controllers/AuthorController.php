<?php

class AuthorController extends Q {

    public $authorInfo = array();
    public $adminLogin = false;

    public function init() {
        parent::init();
        $this->layout = 'author';
        $id = zmf::val('id', 1);
        if(!$id && !$this->uid){
            throw new CHttpException(404, '您所查看的作者不存在，请核实');
        }elseif (!$this->userInfo['authorId']) {
            throw new CHttpException(404, '您所查看的作者不存在，请核实');
        }else{
            $id=  $this->userInfo['authorId'];
        }        
        $this->authorInfo = Authors::getOne($id);
        if ($this->uid) {
            $this->adminLogin=  Authors::checkLogin($this->userInfo,$id);
        }
    }

    public function actionView() {
        $posts = Books::model()->findAll(array(
            'condition' => 'aid=:aid',
            'select' => 'id,colid,title,faceImg,`desc`,words,cTime',
            'params' => array(
                ':aid' => $this->authorInfo['id']
            )
        ));
        $this->selectNav='index';
        $data = array(
            'posts' => $posts
        );
        $this->render('view', $data);
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
        $chapters=  Chapters::getByBook($bid);
        
        $data=array(
            'info'=>$info,
            'chapters'=>$chapters,
        );
        $this->render('book', $data);
    }
    
    public function actionAddChapter($cid=''){        
        if ($cid) {
            $model = Chapters::getOne($cid);
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
            $model->attributes = $_POST['Chapters'];
            if ($model->save()) {
                $this->redirect(array('author/book','bid'=>$model->bid));                
            }
        }
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

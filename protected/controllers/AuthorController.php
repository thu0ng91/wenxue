<?php

class AuthorController extends Q {
    public $authorInfo=array();
    
    public function init() {
        parent::init();
        $this->layout='author';
        $id=  zmf::val('id',1);
        $this->authorInfo=  Authors::getOne($id);
    }

    public function actionIndex() {
        $this->render('index', $data);
    }

    public function actionView() {
        $posts=  Books::model()->findAll(array(
            'condition'=>'aid=:aid',
            'select'=>'id,colid,title,faceImg,`desc`',
            'params'=>array(
                ':aid'=>  $this->authorInfo['id']
            )
        ));
        
        $data=array(
            'posts'=>$posts
        );
        $this->render('view', $data);
    }

}

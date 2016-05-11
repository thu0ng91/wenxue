<?php

class BookController extends Q {

    public function actionIndex() {
        $this->render('index', $data);
    }

    public function actionView() {
        $id=  zmf::val('id', 1);
        if(!$id){
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $info=  Books::model()->findByPk($id);
        if(!$info || $info['status']!=Posts::STATUS_PASSED){
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $chapters=  Chapters::getByBook($id);
        
        $data=array(
            'info'=>$info,
            'chapters'=>$chapters,
        );
        $this->render('view', $data);
    }
    
    public function actionChapter(){
        $cid=  zmf::val('cid',2);
        $chapterInfo=  Chapters::model()->findByPk($cid);
        $data=array(
            'chapterInfo'=>$chapterInfo,
            'chapters'=>$chapters,
        );
        $this->render('chapter', $data);
    }

}

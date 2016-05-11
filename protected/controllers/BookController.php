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
        $data=array(
            'info'=>$info
        );
        $this->render('view', $data);
    }

}

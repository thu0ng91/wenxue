<?php

class BookController extends Q {

    public function actionIndex() {
        $this->render('index', $data);
    }

    public function actionView() {
        $this->render('view', $data);
    }

}

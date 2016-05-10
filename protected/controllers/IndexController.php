<?php

class IndexController extends Q {

    public function actionIndex() {
        $posts=  Books::getIndexTops();
        $data = array(
            'posts' => $posts
        );
        $this->render('index', $data);
    }

}

<?php

class IndexController extends Q {

    public function actionIndex() {        
        $posts=  Showcases::getPagePosts('returnIndexColumns');
        $data = array(
            'posts' => $posts
        );
        $this->render('index', $data);
    }

}

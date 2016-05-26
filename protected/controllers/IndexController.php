<?php

class IndexController extends Q {

    public function actionIndex() {        
        $posts=  Showcases::getPagePosts('returnIndexColumns');
        $this->pageTitle=  zmf::config('sitename').' - '.zmf::config('shortTitle');
        $data = array(
            'posts' => $posts
        );
        $this->render('index', $data);
    }

}

<?php

class IndexController extends Q {

    public function actionIndex() {        
        $posts=  Showcases::getPagePosts('returnIndexColumns',NULL,FALSE,'c640100',  $this->isMobile ? 'w240' : 'w120');
        $this->pageTitle=  zmf::config('sitename').' - '.zmf::config('shortTitle');
        $this->mobileTitle=zmf::config('sitename');
        $data = array(
            'posts' => $posts
        );
        $this->render('index', $data);
    }

}

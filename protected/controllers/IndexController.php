<?php

class IndexController extends Q {

    public function actionIndex() {
        $posts=  Showcases::getPagePosts('returnIndexColumns',NULL,FALSE,'c640100',  $this->isMobile ? 'w240' : 'w120');        
        //取最近更新
        $_sql="SELECT b.id,b.aid,b.colid,'' AS colTitle,b.title,b.faceImg,b.desc,'' AS authorName FROM {{books}} b WHERE b.status=".Posts::STATUS_PASSED." ORDER BY b.updateTime DESC LIMIT 10";
        $_posts=  Yii::app()->db->createCommand($_sql)->queryAll();
        $posts['indexRight3']=array(
            'title'=>'最近更新',
            'display'=>'thumbFirst',
            'posts'=>$_posts,
        );        
        $this->pageTitle=  zmf::config('sitename').' - '.zmf::config('shortTitle');
        $this->mobileTitle=zmf::config('sitename');
        $this->selectNav='indexPage';
        $this->showLeftBtn=false;
        $data = array(
            'posts' => $posts
        );
        $this->render('index', $data);
    }

}

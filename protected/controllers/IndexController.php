<?php

class IndexController extends Q {

    public function actionIndex() {
        $posts=  Showcases::getPagePosts('returnIndexColumns',NULL,FALSE,'c960100',  $this->isMobile ? 'w240' : 'w120');        
        //取最近更新
        $_sql="SELECT b.id,b.aid,b.colid,'' AS colTitle,b.title,b.faceImg,b.desc,'' AS authorName FROM {{books}} b WHERE b.status=".Posts::STATUS_PASSED." ORDER BY b.updateTime DESC LIMIT 10";
        $_posts=  Yii::app()->db->createCommand($_sql)->queryAll();        
        $posts['indexRight3']=array(
            'title'=>'最近更新',
            'display'=>'thumbFirst',
            'posts'=>$_posts,
        );
        //热门作者
        $_sql="SELECT id,avatar,authorName,content FROM {{authors}} WHERE status=".Posts::STATUS_PASSED." ORDER BY score DESC LIMIT 10";
        $authors=  Yii::app()->db->createCommand($_sql)->queryAll();
        foreach ($authors as $k=>$val){
            $authors[$k]['avatar']= zmf::getThumbnailUrl($val['avatar'],'w120');
        }
        //首页图文
        $_sql="SELECT id,title,url,content,faceImg FROM {{digest}} WHERE status=".Posts::STATUS_PASSED." ORDER BY id DESC LIMIT 8";
        $digestes=  Yii::app()->db->createCommand($_sql)->queryAll();
        foreach ($digestes as $k=>$val){
            $digestes[$k]['faceImg']= zmf::getThumbnailUrl($val['faceImg'],'c120');
        }
        
        $this->pageTitle=  zmf::config('sitename').' - '.zmf::config('shortTitle');
        $this->mobileTitle=zmf::config('sitename');
        $this->selectNav='indexPage';
        $this->showLeftBtn=false;
        if(!$this->isMobile){
            $this->links = Links::getLinks($this->isMobile, 'index', 0);
        }
        $data = array(
            'posts' => $posts,
            'authors' => $authors,
            'digestes' => $digestes,
        );
        $this->render('index', $data);
    }

}
